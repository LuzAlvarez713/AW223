from flask import Blueprint

controladorAlumnoBP=Blueprint('controladorAlumno', __name__)


#ruta para arranque del servidor
@controladorAlumnoBP.route('/')
def inicio():
    return 'Hola soy el Backend del Alumno'

#ruta para insert
controladorAlumnoBP.route('/guardarAlumno', methods=['POST'])
def guardar():
    return 'se guardo el alumno'

#ruta para consulta todo
@controladorAlumnoBP.route('/consultaTodo')
def consultaTodo():
    return 'se consulto todo'

#ruta para actualizar
@controladorAlumnoBP.route('/actualizarAlumno', methods=['PUT'])
def actualizar():
    return 'se actualizo el alumno'

#ruta para eliminar
@controladorAlumnoBP.route('/eliminararAlumno', methods=['DELETE'])
def eliminar():
    return 'se borró el alumno'