import cv2
import numpy as np
from utils.image_processing import preprocess_image

def detect_characters(image_path):
    # Preprocess the image
    original, processed = preprocess_image(image_path)
    if original is None:
        return
    
    # Find contours
    contours, _ = cv2.findContours(processed, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    # Filter and process contours
    for cnt in contours:
        x, y, w, h = cv2.boundingRect(cnt)
        
        # Filter small detections
        if w < 20 or h < 20:
            continue
        
        # Draw rectangle around the character
        cv2.rectangle(original, (x, y), (x + w, y + h), (0, 255, 0), 2)
    
    # Display results
    cv2.imshow('Basic Detection - Original', original)
    cv2.imshow('Basic Detection - Processed', processed)
    cv2.waitKey(0)
    cv2.destroyAllWindows()

if __name__ == "__main__":
    image_path = 'samples/sample_text.jpg'
    detect_characters(image_path)