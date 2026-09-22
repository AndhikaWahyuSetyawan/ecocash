from fastapi import FastAPI, File, HTTPException, UploadFile
from PIL import Image, UnidentifiedImageError
from io import BytesIO
from app.config import MODEL_PATH
from app.schemas import PredictionResponse
from app.services.waste_classifier import WasteClassifier
app=FastAPI(title='ECOCASH AI Service')
classifier=None
@app.on_event('startup')
def load_model():
    global classifier
    classifier=WasteClassifier()
@app.get('/health')
def health(): return {'status':'ok','model_loaded':classifier is not None}
@app.post('/api/v1/predict', response_model=PredictionResponse)
async def predict(image: UploadFile=File(...), confidence: float|None=None, iou: float=0.7, imgsz: int=640):
    if not image.content_type or not image.content_type.startswith('image/'): raise HTTPException(422,'Invalid image')
    try: picture=Image.open(BytesIO(await image.read())).convert('RGB')
    except (UnidentifiedImageError, OSError): raise HTTPException(422,'Invalid image')
    return classifier.predict(picture, confidence=confidence or 0.25, iou=iou, imgsz=imgsz)
