import time
from PIL import Image
from ultralytics import YOLO
from app.config import MODEL_PATH, CONFIDENCE_THRESHOLD, IMAGE_SIZE
class WasteClassifier:
    def __init__(self):
        if not MODEL_PATH.exists(): raise FileNotFoundError(f'Model not found: {MODEL_PATH}')
        self.model = YOLO(str(MODEL_PATH))
    def predict(self, image: Image.Image, confidence=CONFIDENCE_THRESHOLD, iou=0.7, imgsz=IMAGE_SIZE):
        started=time.perf_counter(); result=self.model.predict(image, conf=confidence, iou=iou, imgsz=imgsz, verbose=False)[0]; names=result.names
        detections=[]
        for box in result.boxes:
            coords=box.xyxy[0].tolist(); class_id=int(box.cls[0].item())
            detections.append({'class_id':class_id,'class_name':names[class_id],'confidence':round(float(box.conf[0].item()),5),'bbox':{'x1':coords[0],'y1':coords[1],'x2':coords[2],'y2':coords[3]}})
        return {'success':True,'detections':detections,'count':len(detections),'processing_time_ms':round((time.perf_counter()-started)*1000)}
