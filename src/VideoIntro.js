import React from "react";
import IntroVideoFile from "./video/videofile.mp4";
import "./Css/videointro.css";
import { useState } from "react";
import PageComponent from "./PageComponent.js";
const VideoIntro = () => {
  const [played, setPlayed] = useState(false);
  if (played) {
    return <PageComponent />;
  }
  const Handlevideoend = () => {
    setPlayed(true);
  }
  return (
    <div className="video-intro">
      <video autoPlay onEnded={Handlevideoend} muted style={{ width: "100%", height: "100%" }}>
        <source  src={IntroVideoFile} type="video/mp4" />
      </video>
    </div>
  );
};
export default VideoIntro;
