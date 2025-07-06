import { Link } from 'react-router-dom';
import ContentWithSearch from '../components/content/ContentWithSearch';
/**
 * 
 * Show all the countries from the affiliation table, allows for searching of countries by name
 * @author Louis Figes
 * @generated GitHub Copilot was used in the creation of this code.
 * 
 */

function CountriesPage() {

  function renderCountry(country, index) {
    return (
      <Link to={`/countries/${country.country}`} className="" key={index}>
        <div className="content-item flex flex-col h-full overflow-hidden p-2" key={index}>
          <p className="word-wrap break-words">{country.country}</p>
        </div>
      </Link>
    );
  }

  function filterCountries(data, search) {
    return data ? data.filter(item =>
      item.country.toLowerCase().includes(search.toLowerCase())
    ) : [];
  }

  return (
    <div className="page text-center">
      <div>
        <h2>Countries</h2>
      </div>
      <ContentWithSearch gridLayout={true} endpoint={'country'} returnLink={'/'} filterFunction={filterCountries} renderedItem={renderCountry}/>
    </div>
  );
}

export default CountriesPage;
