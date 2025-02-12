
import React, { useState } from "react";
import axios from "axios";
//import "../../css/VideoEnchancer.css";

function VideoEnchancerJSX() {
    const [file, setFile] = useState(null);
    const [enchancedVideo, setEnchancedVideo] = useState("");
    const [loading, setLoading] = useState(false);
    const [AIresponse, setAIresponse] = useState(""); // Defined AIresponse
    const [score, setScore] = useState(0); // Defined score

    const processVideo = () => {
        setLoading(true); // Set loading before request starts

        axios.post("http://localhost:5000/UploadVideo", {
            response: AIresponse,
            score: score,
        })
        .then(response => {
            console.log(response.data);
            setEnchancedVideo(response.data.videoUrl); // Example: Handle response
        })
        .catch(error => {
            console.error("Error submitting video:", error);
        })
        .finally(() => {
            setLoading(false); // Reset loading state
        });
    };

    return (
        <div className="Video-Container">
            <h2>Video Enhancer</h2>
            <input type="file" onChange={(e) => setFile(e.target.files[0])} />
            <button onClick={processVideo} disabled={loading}>
                {loading ? "Processing..." : "Upload Video"}
            </button>
            {enchancedVideo && <video src={enchancedVideo} controls />}
        </div>
    );
}

export default VideoEnchancerJSX;
