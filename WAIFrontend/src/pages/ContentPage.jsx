import { useState, useEffect } from 'react';
import ContentHeader from '../components/content/ContentHeader';
import Sidebar from "../components/content/ContentSidebar";
import SelectedContent from "../components/content/SelectedContent";
import useFetchData from '../hooks/useFetchData';
import { useParams } from 'react-router-dom';

/**
 * 
 * Shows the content from the content table and associated tables
 * @author Louis Figes
 * @generated GitHub Copilot was used in the creation of this code.
 * 
 * Can use id as parameter, this isnt the most secure method but it works
 */
function ContentPage() {
  const [searching, setSearching] = useState(false);
  const [selectedId, setSelectedId] = useState(null);
  const [type, setType] = useState();
  const [page, setPage] = useState(1);
  const [award, setAward] = useState(false);
  const [search, setSearch] = useState("");
  const [debouncedSearch, setDebouncedSearch] = useState("");
  const { id } = useParams();
  const contentId = Number(id);

  /**
   * load the page of content from the database
   */
  const { data: content, error, setEndpoint: setContentEndpoint, reloadData } = useFetchData(`content/?${`page=${page}`}`);

  /**
   * Load the selected item
   */
  const { data: contentTypes } = useFetchData('content-types/');

  /**
   * This useeffect sets the page to 1 so that if a user searches for something new theyre put back to the first page
   * doesnt seem to have other negative effects 
   */
  useEffect(() => {
    if (type || award || debouncedSearch.length > 0) {
      setPage(1);
    }
  }, [type, award, debouncedSearch]);

  /**
   * This useeffect sets the endpoint for the content to be loaded
   */
  useEffect(() => {
    let endpoint = `content/?page=${page}`;
    if (type) {
      endpoint += `&type=${type}`;
    }
    if (award) {
      endpoint += `&award=${award}`;
    }
    if (debouncedSearch.length > 0) {
      endpoint += `&search=${debouncedSearch}`;
    }
    setContentEndpoint(endpoint);
    reloadData();
  
    if (content && content.length > 0 && !id) {
      setSelectedId(content[0].contentId);
    }
  }, [page, type, award, debouncedSearch]);

  /**
   * This useeffect sets the debounced search to the search value
   */
  useEffect(() => {
    const timer = setTimeout(() => {
      setDebouncedSearch(search); 
    }, 300);

    return () => {
      clearTimeout(timer);
    };
  }, [search]);

  const handleSelectItem = (item) => {
    setSelectedId(item.id);
  }

  const handleSearchChange = (e) => {
    setSearch(e.target.value);
    setSearching(!searching);
  };

  function nextPage() {
    setPage((prevPage) => prevPage + 1);
  }

  function backPage() {
    if (page > 1) {
      setPage((prevPage) => prevPage - 1);
    }
  }

  useEffect(() => {
    if (content && content.length > 0 && !id) {
      setSelectedId(content[0].id);
    } else if(id) {
      setSelectedId(id);
    }
  }, [content]);

  return (
    <div className="page flex lg:flex-row flex-col lg:gap-2">
      <div className="lg:max-w-md">
          <ContentHeader
            contentTypes={contentTypes}
            page={page}
            setType={setType}
            nextPage={nextPage}
            backPage={backPage}
            setAward={setAward}
            handleSearchChange={handleSearchChange}
            award={award}
          />
          <div className="pt-2 overflow-y-scroll overflow-x-hidden h-[67vh]">
            <Sidebar onSelectItem={handleSelectItem} content={content} error={error} selectedId={contentId} />
          </div>
      </div>
      <div className="mx-auto overflow-x-auto overflow-y-scroll h-[81vh] w-[90vw] lg:w-[70vw]">
        {selectedId && (
          <SelectedContent contentId={Number(selectedId)} />
        )}
      </div>
    </div>
  );
}

export default ContentPage;