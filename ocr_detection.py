import cv2
import pytesseract
from pytesseract import Output
import numpy as np

# Tesseract path (update if needed)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def preprocess_frame(frame):
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    thresh = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, 
                                 cv2.THRESH_BINARY_INV, 11, 2)
    kernel = np.ones((3, 3), np.uint8)
    processed = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel, iterations=1)
    return processed

def detect_text(frame):
    processed = preprocess_frame(frame)
    custom_config = r'--oem 3 --psm 6'
    details = pytesseract.image_to_data(processed, output_type=Output.DICT, config=custom_config, lang='eng')
    
    for i in range(len(details['text'])):
        if int(details['conf'][i]) > 50:
            x, y, w, h = details['left'][i], details['top'][i], details['width'][i], details['height'][i]
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
            cv2.putText(frame, details['text'][i], (x, y-10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 1)
    return frame

def main():
    cap = cv2.VideoCapture(0)
    
    while True:
        ret, frame = cap.read()
        if not ret:
            break
        
        # Process the frame 
        output_frame = detect_text(frame)
        
        cv2.imshow('Correct Text Detection', output_frame)
        if cv2.waitKey(1) & 0xFF == ord('q'): #kill btn
            break
    
    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    main()