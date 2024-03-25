import './bootstrap';

import ReactDOM from 'react-dom/client';

import Movies from './components/Movies';
import Screenings from './components/Screenings';


if (document.getElementById('screenings')) {
    ReactDOM.createRoot(document.getElementById('screenings')).render(
        <Screenings/>
    );
}

if (document.getElementById('movies')) {
    ReactDOM.createRoot(document.getElementById('movies')).render(
        <Movies/>
    );
}
