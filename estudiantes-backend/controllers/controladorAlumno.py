
from flask import Blueprint, request, jsonify
from Models.modelEstudiante import *


controladorAlumnoBP=Blueprint('controladorAlumno', __name__)


#ruta para arranque del servidor
@controladorAlumnoBP.route('/')
def inicio():
    return 'Hola soy el Backend del Alumno'

#ruta para insert
@controladorAlumnoBP.route('/guardarAlumno', methods=['POST'])
def guardar():
    nombreReq=request.form.get('txtNombre').strip()
    carreraReq=request.form.get('txtCarrera').strip()

    try:
        insertEstudiante(nombreReq,carreraReq)
        return jsonify({'mensaje':'Se guardo el alumno correctamente'}), 200
    
    except Exception as e:
        return jsonify({'mensaje':'Error al guardar el alumno','error':str(e)}), 500

#ruta para consulta todo
@controladorAlumnoBP.route('/consultaTodo', methods=['GET'])
def consultaAll():
    try:
        consulta=selectAll()
        return jsonify(consulta), 200
    except Exception as e:
        return jsonify({'mensaje':'Error al consultar los alumnos','error':str(e)}), 500

#ruta para actualizar
@controladorAlumnoBP.route('/actualizarAlumno', methods=['PUT'])
def actualizar():
    try:
        idReq=request.form.get('txtId')
        nombreReq=request.form.get('txtNombre').strip()
        carreraReq=request.form.get('txtCarrera').strip()

        updateEstudiante(idReq,nombreReq,carreraReq)
        return jsonify({'mensaje':'Se actualizo el alumno correctamente'}), 200
    except Exception as e:
        return jsonify({'mensaje':'Error al actualizar el alumno','error':str(e)}), 500

#ruta para eliminar
@controladorAlumnoBP.route('/eliminararAlumno', methods=['DELETE'])
def eliminar():
    try:
        idReq=request.form.get('txtId')
        softDeleteEstudiante(idReq)
        return jsonify({'mensaje':'Se elimino el alumno correctamente'}), 200
    except Exception as e:
        return jsonify({'mensaje':'Error al eliminar el alumno','error':str(e)}), 500

# Ruta para consultar un estudiante por ID
@controladorAlumnoBP.route('/consultaPorId/<int:id>', methods=['GET'])
def consultaPorId(id):
    try:
        consulta = selectById(id)

        if consulta:
            return jsonify(consulta), 200

        return jsonify({
            'mensaje': 'No se encontro el estudiante'
        }), 404

    except Exception as e:
        return jsonify({
            'mensaje': 'Error al consultar el estudiante',
            'error': str(e)
        }), 500