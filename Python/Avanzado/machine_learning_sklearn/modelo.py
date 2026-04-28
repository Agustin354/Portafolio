import numpy as np
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import Pipeline
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.datasets import load_iris
import joblib

def cargar_datos():
    iris = load_iris()
    return iris.data, iris.target, iris.target_names

def construir_pipelines() -> dict:
    return {
        'regresion_logistica': Pipeline([
            ('scaler', StandardScaler()),
            ('clf', LogisticRegression(max_iter=1000)),
        ]),
        'random_forest': Pipeline([
            ('clf', RandomForestClassifier(n_estimators=100, random_state=42)),
        ]),
        'gradient_boosting': Pipeline([
            ('scaler', StandardScaler()),
            ('clf', GradientBoostingClassifier(n_estimators=100, random_state=42)),
        ]),
    }

def evaluar(pipelines: dict, X_train, X_test, y_train, y_test, nombres_clases):
    resultados = {}
    for nombre, pipe in pipelines.items():
        pipe.fit(X_train, y_train)
        y_pred = pipe.predict(X_test)
        cv_scores = cross_val_score(pipe, X_train, y_train, cv=5)
        resultados[nombre] = {
            'accuracy': pipe.score(X_test, y_test),
            'cv_mean': cv_scores.mean(),
            'cv_std': cv_scores.std(),
        }
        print(f"\n=== {nombre} ===")
        print(classification_report(y_test, y_pred, target_names=nombres_clases))
    return resultados

def guardar_mejor(pipelines, resultados, X_train, y_train):
    mejor = max(resultados, key=lambda k: resultados[k]['accuracy'])
    pipelines[mejor].fit(X_train, y_train)
    joblib.dump(pipelines[mejor], f'modelo_{mejor}.pkl')
    print(f"\nMejor modelo: {mejor} ({resultados[mejor]['accuracy']:.3f})")
    return mejor

if __name__ == "__main__":
    X, y, clases = cargar_datos()
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    pipelines = construir_pipelines()
    resultados = evaluar(pipelines, X_train, X_test, y_train, y_test, clases)
    guardar_mejor(pipelines, resultados, X_train, y_train)
