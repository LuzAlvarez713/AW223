
from extensions import mysql
#funcion para consultar todo
def selectAll():
    cursor=mysql.connection.cursor()
    cursor.execute("SELECT * FROM tb_alumnos WHERE state=1")
    consulta=cursor.fetchall()
    cursor.close()
    return consulta


#funcion para insertar
def insertEstudiante(nombre,carrera):
    cursor=mysql.connection.cursor()
    cursor.execute("INSERT INTO tb_alumnos(nombre, carrera) VALUES(%s,%s)",(nombre, carrera))
    mysql.connection.commit()
    cursor.close()

#funcion para eliminar
def softDeleteEstudiante(id):
    cursor=mysql.connection.cursor()
    cursor.execute("UPDATE tb_alumnos SET state=0 WHERE id=%s",(id,))
    mysql.connection.commit()
    cursor.close()


#funcion para actualizar
def updateEstudiante(id,nombre,carrera):
    cursor=mysql.connection.cursor()
    cursor.execute("UPDATE tb_alumnos SET nombre=%s, carrera=%s WHERE id=%s",(nombre,carrera,id))
    mysql.connection.commit()
    cursor.close()

# Funcion para consultar un estudiante por ID
def selectById(id):
    cursor = mysql.connection.cursor()

    cursor.execute(
        "SELECT * FROM tb_alumnos WHERE id=%s AND state=1",
        (id,)
    )

    consulta = cursor.fetchone()
    cursor.close()

    return consulta



