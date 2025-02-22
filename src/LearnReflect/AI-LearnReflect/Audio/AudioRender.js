import React, { useState } from "react";
import axios from "axios";


function AudioEnchancerJSX() {
  const [file, setFile] = useState(null);
  const [enchancedAudio, setEnchancedAudio] = useState("");
  const [loading, setLoading] = useState(false);
  const [AIresponse, setAIresponse] = useState(""); // Define AIresponse
  const [score, setScore] = useState(0); // Define score

  const processAudio = () => {
    setLoading(true); // Set loading before request starts

    axios
      .post("http://localhost:5000/UploadAudio", {
        response: AIresponse,
        score: score,
      })
      .then((response) => {
        console.log(response.data);
      })
      .catch((error) => {
        console.error("Error submitting feedback:", error);
      })
      .finally(() => {
        setLoading(false); // Reset loading state after request completes
      });
  };

  return (
    <div className="Audio-Container">
      <h2>Audio Enhancer</h2>
      <input type="file" onChange={(e) => setFile(e.target.files[0])} />
      <button onClick={processAudio} disabled={loading}>
        {loading ? "Processing..." : "Upload Audio"}
      </button>
    </div>
  );
}

export default AudioEnchancerJSX;
