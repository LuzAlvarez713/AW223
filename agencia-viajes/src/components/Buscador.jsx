
import React from 'react';

export default function Buscador({ busqueda, setBusqueda }) {
  return (
    <div className="buscador">
      <h2>Encuentra tu próximo destino</h2>

      <input
        type="text"
        placeholder="Buscar por destino o país..."
        value={busqueda}
        onChange={(e) => setBusqueda(e.target.value)}
      />
    </div>
  );
}

