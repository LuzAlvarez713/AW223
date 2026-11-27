from flask import Flask
from config import config
from extensions import mysql


def create_app():
    app = Flask(__name__)

    app.config.from_object(config)

    mysql.init_app(app)

    from controllers.controladorAlumno import controladorAlumnoBP

    app.register_blueprint(controladorAlumnoBP)

    return app


if __name__ == '__main__':
    app = create_app()
    app.run(host='127.0.0.1', port=5000, debug=True)