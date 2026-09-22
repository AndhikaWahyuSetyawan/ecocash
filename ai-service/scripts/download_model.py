from pathlib import Path
from urllib.request import urlretrieve
URL='https://huggingface.co/daylans/waste-classification-yolov8-ken/resolve/main/yolov8n-waste-12cls-best.pt'
target=Path(__file__).parents[1]/'models'/'yolov8n-waste-12cls-best.pt'
target.parent.mkdir(exist_ok=True)
print(f'Downloading model to {target}')
urlretrieve(URL, target)
print('Download complete')
