import HomePage from './pages/HomePage';
import Header from './components/layout/Header';
import Footer from './components/layout/Footer';
import { Routes, Route } from 'react-router-dom';
import CountriesPage from './pages/CountriesPage';
import ContentPage from './pages/ContentPage';
import './App.css'
import AuthPage from './pages/AuthPage';
import AuthProvider from './components/auth/AuthProvider';
import ProfilePage from './pages/ProfilePage';
import Restricted from './components/profile/Restricted';
import Logout from './components/profile/Logout';
import UnAuthed from './components/profile/UnAuthed';
import PageNotFound from './pages/PageNotFound';
import CountryPage from './pages/CountryPage';
import ContentWithSearch from './components/content/ContentWithSearch';
/**
 * Main file for the website
 * Stores the layout and routes
 * @author Louis Figes
 */
const App = () => {
  return (
    <div className="flex flex-col min-h-screen">
        <AuthProvider>
          <Header />
          <div className="flex-grow overflow-x-hidden">
            <Routes>
              <Route path="/" element={<HomePage />}/>
              <Route path="/countries" element={<CountriesPage />}/>
              <Route path="/countries/:country" element={<CountryPage />}/>
              <Route path="/login" element={<UnAuthed><AuthPage/></UnAuthed>}/>
              <Route path='/register' element={<UnAuthed><AuthPage/></UnAuthed>}/>
              <Route path="/content/" element={<ContentPage />}/>
              <Route path="/content/:id" element={<ContentPage />}/>
              <Route path="/profile" element={<Restricted><ProfilePage /></Restricted>}/>
              <Route path="/profile/favourites" element={<Restricted><ContentWithSearch endpoint={'favourites/'} returnLink={'/profile/'} headerText={'Favourites'}/></Restricted>}/>
              <Route path="/logout" element={<Restricted><Logout /></Restricted>}/>
              <Route path="*" element={<PageNotFound />}/>
            </Routes>
          </div>
          <Footer />
        </AuthProvider>
    </div>
  );
};

export default App;
