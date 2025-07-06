import useFetchData from '../../hooks/useFetchData';
import PreviewThumbnail from './PreviewThumbnail';
import { Link } from 'react-router-dom';
/**
 * Component for showing the thumbnails of preview content on the homepage
 * @author Louis Figes
 */
export function PreviewContent() {
    const { data: videos} = useFetchData(`preview?limit=6`);

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            {videos && videos.map(video => (
                <Link key={video.id} to={`content/${video.id}`}>
                    <PreviewThumbnail title={video.title} previewVideo={video.preview_video} />
                </Link>
            ))}
        </div>
    );
}

export default PreviewContent;