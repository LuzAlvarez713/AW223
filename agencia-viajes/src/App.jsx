
import React, { useState } from 'react';

import Header from './components/Header';
import Buscador from './components/Buscador';
import ListaDestinos from './components/ListaDestinos';

import './App.css';

const destinosIniciales = [
  {
    id: 1,
    nombre: 'Cancún',
    ubicacion: 'Quintana Roo, México',
    pais: 'México',
    precio: 8500,
    duracion: '5 días',
    actividad: 'Playa y actividades acuáticas',
    descripcion: 'Disfruta de hermosas playas y aguas cristalinas.',
    imagen: 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?w=600'
  },
  {
    id: 2,
    nombre: 'París',
    ubicacion: 'Francia',
    pais: 'Francia',
    precio: 18000,
    duracion: '7 días',
    actividad: 'Museos y recorridos turísticos',
    descripcion: 'Conoce la Torre Eiffel y disfruta de la cultura francesa.',
    imagen: 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=600'
  },
  {
    id: 3,
    nombre: 'Tokio',
    ubicacion: 'Japón',
    pais: 'Japón',
    precio: 22000,
    duracion: '8 días',
    actividad: 'Tecnología y cultura',
    descripcion: 'Explora una ciudad llena de tecnología y tradición.',
    imagen: 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=600'
  },
  {
    id: 4,
    nombre: 'Roma',
    ubicacion: 'Italia',
    pais: 'Italia',
    precio: 16000,
    duracion: '6 días',
    actividad: 'Historia y arquitectura',
    descripcion: 'Descubre monumentos históricos y lugares increíbles.',
    imagen: 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=600'
  },
  {
    id: 5,
    nombre: 'Nueva York',
    ubicacion: 'Estados Unidos',
    pais: 'Estados Unidos',
    precio: 19500,
    duracion: '6 días',
    actividad: 'Compras y entretenimiento',
    descripcion: 'Visita Times Square y disfruta de la gran ciudad.',
    imagen: 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=600'
  },
  {
    id: 6,
    nombre: 'Santorini',
    ubicacion: 'Grecia',
    pais: 'Grecia',
    precio: 25000,
    duracion: '7 días',
    actividad: 'Paisajes y recorridos',
    descripcion: 'Conoce sus hermosas casas blancas y vistas al mar.',
    imagen: 'https://images.unsplash.com/photo-1533105079780-92b9be482077?w=600'
  }
];

function App() {

  const [busqueda, setBusqueda] = useState('');
  const [favoritos, setFavoritos] = useState([]);

  const destinosFiltrados = destinosIniciales.filter((destino) =>
    destino.nombre.toLowerCase().includes(busqueda.toLowerCase()) ||
    destino.ubicacion.toLowerCase().includes(busqueda.toLowerCase())
  );

  const agregarFavorito = (id) => {

    if (favoritos.includes(id)) {
      setFavoritos(favoritos.filter((favorito) => favorito !== id));
    } else {
      setFavoritos([...favoritos, id]);
    }

  };

  return (
    <div className="app">

      <Header cantidadFavoritos={favoritos.length} />

      <main>

        <Buscador
          busqueda={busqueda}
          setBusqueda={setBusqueda}
        />

        <ListaDestinos
          destinos={destinosFiltrados}
          favoritos={favoritos}
          agregarFavorito={agregarFavorito}
        />

      </main>

      <footer>
        <p>© 2026 Travel Dreams | Tu próxima aventura comienza aquí ✈️</p>
      </footer>

    </div>
  );
}

export default App;
