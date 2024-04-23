import numpy as np
import pandas as pd
import sklearn
import tensorflow as tf
import pymysql
import sshtunnel

from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler
from tensorflow.keras.models import load_model

from flask import Flask, request

# Set up SSH tunnel
server = sshtunnel.SSHTunnelForwarder(
    ('selene.hud.ac.uk', 22),
    ssh_username='workit',
    ssh_password='umbra(despair>Quartz218',
    remote_bind_address=('localhost', 3306)
)
server.start()

# Defining the connection
connection = pymysql.connect(host='localhost', port=server.local_bind_port, user='workit', password='umbra(despair>Quartz218', database='workit')

MODEL_PATH = "AI/source/ann_gym_recommender.keras"

# Load the dataset
dataset = pd.read_sql_query("SELECT * FROM `megaGymDataset`", connection)

server.stop()

def labelEncodersScaler(data):
    label_encoders = {}
    for feature in ["Type", "BodyPart", "Equipment", "Level"]:
        label_encoders[feature] = LabelEncoder()
        data[feature] = label_encoders[feature].fit_transform(data[feature])

    X = data[["Type", "BodyPart", "Equipment", "Level"]]
    y = data["ID"]
    
    # Split the data into training and testing sets
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # Standardize the features
    scaler = StandardScaler()
    X_train = scaler.fit_transform(X_train)

    return label_encoders, scaler

def train_model(data):
    label_encoders, scaler = labelEncodersScaler(data)
    
    X = data[["Type", "BodyPart", "Equipment", "Level"]]
    y = data["ID"]
    
    # Split the data into training and testing sets
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    X_train = scaler.fit_transform(X_train)
    X_test = scaler.transform(X_test)
    
    # Build the ANN model using TensorFlow
    model = tf.keras.models.Sequential([
        tf.keras.layers.Dense(64, activation='relu', input_shape=(X_train.shape[1],)),
        tf.keras.layers.Dense(64, activation='relu'),
        tf.keras.layers.Dense(len(data["ID"].unique()), activation='softmax')  # Use the unique number of IDs as the output dimension
    ])
    
    model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])
    
    model.fit(X_train, y_train, epochs=10, batch_size=32, validation_data=(X_test, y_test))

    return model

def recommend_exercises(user_input, model, data):
    label_encoders, scaler = labelEncodersScaler(data)
    
    user_input_encoded = [label_encoders[feature].transform([user_input[i]])[0] for i, feature in enumerate(["Type", "BodyPart", "Equipment", "Level"])]
    user_input_scaled = scaler.transform([user_input_encoded])
    
    predictions = model.predict(user_input_scaled)
    
    return data.iloc[np.squeeze(predictions.argsort())][::-1][:10].sort_values(["Rating"], ascending=False).index.to_list()


# user_input = ["Strength", "Biceps", "Body Only", "Intermediate"]
# IDs = recommend_exercises(user_input, model, dataset.copy())
# dataset.iloc[IDs]


app = Flask(__name__)

@app.route('/')
def index():
    # Training from dataset
    model = train_model(dataset.copy())
    model.save(MODEL_PATH)

    # Predict from user input
    model = load_model(MODEL_PATH)

    user_input = [str(request.args.get("workout-type"))]
    user_input += [str(request.args.get("body-part"))]
    user_input += [str(request.args.get("equipment"))]
    user_input += [str(request.args.get("level"))]
  
    IDs = recommend_exercises(user_input, model, dataset.copy())
    return dataset.iloc[IDs].to_json(orient='records')

if __name__ == '__main__':
    app.run(debug=True)