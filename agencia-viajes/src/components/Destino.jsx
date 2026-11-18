
import React, { useState } from 'react';

export default function Destino({ destino, agregarFavorito, esFavorito }) {

  const [mostrarDetalles, setMostrarDetalles] = useState(false);

  return (
    <div className="tarjeta">

      <img src={destino.imagen} alt={destino.nombre} />

      <div className="contenido-tarjeta">

        <h3>{destino.nombre}</h3>

        <p>📍 {destino.ubicacion}</p>

        <h3 className="precio">
          ${destino.precio.toLocaleString('es-MX')} MXN
        </h3>

        <p>{destino.descripcion}</p>

        <button
          className="btn-favorito"
          onClick={() => agregarFavorito(destino.id)}
        >
          {esFavorito ? '❤️ Quitar favorito' : '♡ Agregar favorito'}
        </button>

        <button
          className="btn-detalles"
          onClick={() => setMostrarDetalles(!mostrarDetalles)}
        >
          {mostrarDetalles ? 'Ocultar detalles' : 'Ver detalles'}
        </button>

        {mostrarDetalles && (
          <div className="detalles">
            <p><strong>País:</strong> {destino.pais}</p>
            <p><strong>Duración:</strong> {destino.duracion}</p>
            <p><strong>Actividad:</strong> {destino.actividad}</p>
          </div>
        )}

      </div>
    </div>
  );
}
