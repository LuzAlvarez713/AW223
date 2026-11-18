
import React from 'react';
import Destino from './Destino';

export default function ListaDestinos({
  destinos,
  favoritos,
  agregarFavorito
}) {

  return (
    <div className="contenedor-destinos">

      {destinos.length === 0 ? (
        <p>No se encontraron destinos.</p>
      ) : (

        destinos.map((destino) => (

          <Destino
            key={destino.id}
            destino={destino}
            agregarFavorito={agregarFavorito}
            esFavorito={favoritos.includes(destino.id)}
          />

        ))

      )}

    </div>
  );
}
