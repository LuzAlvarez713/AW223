
import React from 'react';

export default function Header({ cantidadFavoritos }) {
  return (
    <header className="header">
      <h1>✈️ Travel </h1>

      <nav>
        <span>Inicio</span>
        <span>Destinos</span>
        <span>Favoritos: ❤️ {cantidadFavoritos}</span>
      </nav>
    </header>
  );
}
