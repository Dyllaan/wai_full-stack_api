import { useParams } from 'react-router-dom';
import { Link } from 'react-router-dom';
import ContentWithSearch from '../components/content/ContentWithSearch';

/**
 * 
 * Shows the content from the content table and associated tables
 * @author Louis Figes
 * @generated GitHub Copilot was used in the creation of this code.
 * 
 * Can use id as parameter, this isnt the most secure method but it works
 */
function CountryPage() {
  const { country } = useParams();

  function renderContent(item, index) {
    return (
      <Link to={`/content/${item.id}`} key={index}>
        <div className="content-item" key={index}>
          <p className="truncate text-lg">{item.title}</p>
          <div className="flex gap-2 text-center">
            <p className="text-red-100">{item.city}</p>
            <p className="text-red-200">{item.institution}</p>
            <p className="text-red-300">{item.author}</p>
          </div>
        </div>
      </Link>
    );
  }

  return (
    <div className="page">
      <div className="text-center">
        <h2>{country}</h2>
      </div>
      <ContentWithSearch startParams={`country=${country}`} enablePagination={true} enableDebouncing={true} returnLink={'countries/'} endpoint={`author-and-affiliation/`} renderedItem={renderContent} />
    </div>
  );
}

export default CountryPage;