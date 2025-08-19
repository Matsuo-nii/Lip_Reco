import cv2
import pytesseract
from pytesseract import Output
import numpy as np
import mysql.connector

# Tesseract path (update if needed)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# Database connection (update with your credentials)
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="voir_db"  # your DB name
)
cursor = db.cursor()

def preprocess_frame(frame):
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    thresh = cv2.adaptiveThreshold(
        gray, 255,
        cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY_INV, 11, 2
    )
    kernel = np.ones((3, 3), np.uint8)
    processed = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel, iterations=1)
    return processed

def check_plate_in_db(plate_text):
    """Check if OCR result exists in DB"""
    query = "SELECT * FROM vehicles WHERE license_plate = %s"
    cursor.execute(query, (plate_text,))
    result = cursor.fetchone()
    return result is not None

def detect_text(frame):
    processed = preprocess_frame(frame)
    custom_config = r'--oem 3 --psm 6'
    details = pytesseract.image_to_data(
        processed, output_type=Output.DICT,
        config=custom_config, lang='eng'
    )
    
    recognized_texts = []

    for i in range(len(details['text'])):
        if int(details['conf'][i]) > 50:
            text = details['text'][i].strip().upper()
            if text != "":
                recognized_texts.append(text)

            x, y, w, h = details['left'][i], details['top'][i], details['width'][i], details['height'][i]
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
            cv2.putText(frame, details['text'][i], (x, y-10),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 1)
    
    # Combine detected text into one string (simulate plate recognition)
    if recognized_texts:
        plate_candidate = "".join(recognized_texts)
        if check_plate_in_db(plate_candidate):
            cv2.putText(frame, f"AUTHORIZED: {plate_candidate}", (50, 50),
                        cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 3)
            print(f"✅ Authorized vehicle detected: {plate_candidate}")
        else:
            cv2.putText(frame, f"UNAUTHORIZED: {plate_candidate}", (50, 50),
                        cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 3)
            print(f"❌ Unauthorized vehicle detected: {plate_candidate}")

    return frame

def main():
    cap = cv2.VideoCapture(0)
    
    while True:
        ret, frame = cap.read()
        if not ret:
            break
        
        # Process the frame 
        output_frame = detect_text(frame)
        
        cv2.imshow('License Plate OCR', output_frame)
        if cv2.waitKey(1) & 0xFF == ord('q'): #kill btn
            break
    
    cap.release()
    cv2.destroyAllWindows()
    cursor.close()
    db.close()

if __name__ == "__main__":
    main()
