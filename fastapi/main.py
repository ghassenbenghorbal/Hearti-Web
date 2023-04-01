from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from sklearn.compose import make_column_transformer
from sklearn.pipeline import make_pipeline
from sklearn.impute import SimpleImputer
from sklearn.preprocessing import (StandardScaler,OneHotEncoder)
import uvicorn
import numpy as np
import tensorflow as tf
import pandas as pd

# Define the input data schema using Pydantic BaseModel
class HeartDiseaseData(BaseModel):
    age: int
    sex: int
    chestPain: int
    bloodPressure: int
    cholestoral: int
    fastingBloodSugar: int
    restingEcg: int
    maxHeartRate: int
    exerciseAngina: int
    oldpeak: float
    slope: int
    numVessels: int
    thal: int
    
# Define the FastAPI app
app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)
# Load the trained model
model = tf.keras.models.load_model('heart_disease_model.h5')
heart_data = pd.read_csv('heart.csv')

#Preparing pipelines
transformer_num = make_pipeline(
    SimpleImputer(strategy="most_frequent"),
    StandardScaler(),
)
transformer_cat = make_pipeline(
    SimpleImputer(strategy="most_frequent"),
    OneHotEncoder(handle_unknown='ignore'),
)
    #Organizing columns
numerical_columns = ['age', 'trestbps', 'chol', 'thalach', 'oldpeak']
categorical_columns = ['sex', 'cp', 'fbs', 'restecg', 'exang', 'slope', 'ca', 'thal']

    #Creating the preprocessor
preprocessor = make_column_transformer(
                (transformer_num, numerical_columns),
                (transformer_cat, categorical_columns)
            )
preprocessor.fit(heart_data)

# Define the API endpoint
@app.post('/predict')
async def predict_heart_disease(data: HeartDiseaseData):
    # Check if data.age, data.sex, data.chestPain, data.bloodPressure, data.cholestoral, data.fastingBloodSugar, data.restingEcg, data.maxHeartRate, data.exerciseAngina, data.oldpeak, data.slope, data.numVessels, data.thal are valid
        
    
    # Convert input data to a numpy array with the correct shape
    input_data = pd.DataFrame([[data.age, data.sex, data.chestPain, data.bloodPressure, data.cholestoral, data.fastingBloodSugar, data.restingEcg, 
                            data.maxHeartRate, data.exerciseAngina, data.oldpeak, data.slope, data.numVessels, data.thal]], 
                            columns=['age', 'sex', 'cp', 'trestbps', 'chol', 'fbs', 'restecg', 'thalach',
                                     'exang', 'oldpeak', 'slope', 'ca', 'thal'], index=[0])
    
    # return {'input_data': input_data.head().to_dict(orient='records')}

    
    # Normalize the input data using the same scaling used during training
    
        
        # Preprocess the input data
    input_data = preprocessor.transform(input_data)
    # Make a prediction using the trained model
    prediction = model.predict(input_data)
    # Return the predicted heart disease risk as a JSON object
    return {'heart_disease_risk': round(float(prediction[0][0]))}

@app.get('/')
def index():
    return {'message': 'Hello World'}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8082)