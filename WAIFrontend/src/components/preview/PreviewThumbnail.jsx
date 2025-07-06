import PropTypes from 'prop-types';
import getYouTubeID from './getYouTubeID';

/**
 * Simple component to display an image with a link, used for preview videos
 * @author Louis Figes
 */

function PreviewThumbnail(props) {
  const { title, previewVideo } = props;
  const youtubeThumbnail = `https://img.youtube.com/vi/${getYouTubeID(previewVideo)}/0.jpg`;

  return (
    <div className="rounded-lg hover:cursor-pointer hover-scale">
      <p className="text-white truncate">{title}</p>
      <img src={youtubeThumbnail} className="w-full rounded-lg" />
    </div>
  );
}

PreviewThumbnail.propTypes = {
  title: PropTypes.string.isRequired,
  previewVideo: PropTypes.string.isRequired,
}

export default PreviewThumbnail;