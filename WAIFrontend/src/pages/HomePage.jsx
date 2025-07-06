import PreviewContent from "../components/preview/PreviewContent";
import useFetchData from "../hooks/useFetchData";
import Loading from "../components/Loading";
import { useEffect,useState  } from "react";
/**
 * 
 * Simple homepage
 * @author Louis Figes
 * @generated GitHub Copilot was used in the creation of this code.
 * 
 */

function HomePage() {
  const { data: contentTypes, loading: typesLoading } = useFetchData('content-types/');
  const { data: contentCount, loading: contentLoading } = useFetchData('content/?count=true');
  const [count, setCount] = useState(0);
  const howManyTypes = contentTypes && contentTypes.length;
  
  useEffect(() => {
    if(!contentLoading) {
      setCount(getCount());
    }
  }, [contentLoading]);

  const getCount = () => {
    if(contentCount) {
      return contentCount[0].count;
    }
  }

  if(typesLoading || contentLoading) {
    return (
      <Loading/>
    )
  }
  
  return (
      <div className="flex flex-col text-center">
        {
          /**
           * Image of books with div overlay
           */
        }
        <div className="border border-slate-400 text-neutral-300 bg-education bg-cover bg-center-center bg-no-repeat min-h-[200px] flex">
          <div className="absolute w-24">
            <p className="bg-opacity-[80%] bg-black text-white">Photo by <a href="https://unsplash.com/@syinq?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Susan Q Yin</a> on <a href="https://unsplash.com/photos/books-on-brown-wooden-shelf-2JIvboGLeho?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a></p>
          </div>
          <div className="basic-div bg-opacity-[95%] w-3/4 my-auto">
            <h1 className="text-4xl">Welcome to CHI 2023</h1>
            <p>This is <span>NOT</span> an official CHI website or in any way affiliated.</p>
            <p>This website allows users to explore content from the CHI 2023 conference.</p>
            <p>Users can also register and login to add notes to content.</p>
          </div>
        </div>
        <div className="px-12 py-4 flex flex-col gap-2">
          <div className="secondary rounded-3xl p-4 flex flex-col lg:flex-row gap-2">
            <div className="homepage-info">
              <h2>Variety</h2>
              <p>This implemention stores content on {howManyTypes} types of content.</p>
            </div>
            <div className="homepage-info">
              <h2>DOI</h2>
              <p>Content on this cite provides the DOI links of content, for your convienence.</p>
            </div>
            <div className="homepage-info">
              <h2>Safety</h2>
              <p>This site handles your data safely.</p>
              <p>We will never sell or distribute your information.</p>
            </div>
            <div className="homepage-info">
              <h2>Videos</h2>
              <p>This site has associated videos to enhance your understanding.</p>
            </div>
            <div className="homepage-info">
              <h2>Expansive</h2>
              <p>This implemention stores {count} different content items.</p>
            </div>
            <div className="homepage-info">
              <h2>Personalise</h2>
              <p>Make sure to favourite and leave notes on content you enjoy!</p>
            </div>
          </div>
          <div>
            <PreviewContent />
          </div>
        </div>
      </div>
  );
}

export default HomePage;
