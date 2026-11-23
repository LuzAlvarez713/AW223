from flask import Flask

app = Flask(__name__)

@app.route('/')
def Inicio():
    return "Hola Mundo  Flask!"

@app.route('/soyotraruta')
def otra():
    return "esta es la dirección de otra ruta"

if __name__ == '__main__':
    app.run(debug=True)