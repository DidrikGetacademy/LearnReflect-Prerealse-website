import React, { useState } from "react";
import axios from "axios";
//import "../../css/ChatGpt.css";




function Chatbot() {
  const MAX_TOKENS = 4096; //[IMPORTANT] CHECK THE MODELS TOKEN LIMIT!
  const [Usermessage, setUsermessage] = useState(""); //User response state
  const [assistantResponse, setAssistantResponse] = useState(""); //Assistant respone state
  const [score, setScore] = useState(null); // FeedBack Reinforcement Learning score
  const [feedbackSubmitted, setFeedbackSubmitted] = useState(false); //tracking if feedback is submitted
  const [loading, setLoading] = useState(false); // Loding state for requests
  const [feedbackText,setFeedbackText] = useState("")
  const [conversationHistory, setConversationHistory] = useState([ { role: "system", content: "You are a helpful motivational assistant designed to help individuals with self-improvement. Your goal is to inspire, encourage, and provide practical advice for people working toward their personal growth, goals, and better habits. Be empathetic, supportive, and positive in all your responses. You should listen to the user's concerns, offer motivating feedback, suggest actionable steps for improvement, and maintain a tone of encouragement and empowerment. Help users believe in themselves, while also providing useful and realistic advice for self-growth."}])



  //FUNCTION that calculate/estimate the token count based on the length of message/conversation history.
  const estimateTokenCount = (messages) => {
    let totalTokens = 0;
    messages.foreach(message => {
      const content = message.content;
      totalTokens += Math.ceil(content.length / 4); //Approximate 1 token = 4 characters
    });
    return totalTokens
  }


  //Function that trims the conversation history too always be valid within the MAX_TOKENS value set.
  const trimConversationHistory = (history) => {
    let tokenCount = estimateTokenCount(history);

    while(tokenCount > MAX_TOKENS){
      history.shift() //Removes the first (oldest) message in conversation history
      tokenCount = estimateTokenCount(history);
    }
    return history
  };




//REQUEST RESPONSE FROM MODEL API
  const RequestAssistantResponse = async () => {
    if (Usermessage.trim() === "") return; //Stops invalid usermessage early.
    setLoading(true);// Show the loading state
    const newUserMessage = { role: 'user', content: Usermessage};
    const updatedHistory = [...conversationHistory, newUserMessage]; // Adds the user message to the history

    const trimmedHistory = trimConversationHistory(updatedHistory) //Trims conversation history before sending API POST


    const requestBody = {
      message: trimmedHistory //Sends the entire conversation with the user and Assistant
    };
    try {
      const response = await fetch("http://localhost:5000//chatbot/chat", {
        method: "POST",
        headers: {
          "Content-type": "application/json"
        },
        body: JSON.stringify(requestBody)
      });
      if (!response.ok) {
        const errorDetails = await response.json() // Attempts to parse the response body as JSON 
        console.error("Error response status:", response.status) //logs the http status code
        console.error("Error response body:", errorDetails)  //logs the response body (if available)
        throw new Error(`Network response was not ok.. ${response.status}`);
      }
      const data = await response.json(); //response dta from backend.
      const assistantMessage = {role: "assistant", content: data.response} // the assistant response 

      const updatedConversationHistory = [...updatedHistory, assistantMessage]    //Updates the conversation history by appending both user and assistant messages
      setConversationHistory(updatedConversationHistory) //Saves the updated history
      
      setAssistantResponse(data.response); //Saves the assistant response to display
      setUsermessage(""); //clears the user input field
      setFeedbackSubmitted(false)
     } catch (error) {
        console.error('Error:', error);
        if (error.response) {
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
        } else {
          console.log('Error communicating with the server. Please try again. possible solution (network error etc)'); // Alerts error incase network error etc
        }
    } finally {
      setLoading(false); //  Hides the loading state
    }
  };



//FEEEDBACK TO MODEL API
  const handleFeedbackTextChange = (e) => {
    setFeedbackText(e.target.value)
    console.log("FeedbackText:", feedbackText)
  }

  //Function (Handles feedback submission score)
  const handleFeedback = scoreValue => {
    setScore(scoreValue);
    console.log("Feedback score set to:", scoreValue); 
  };

  //Submits feedback to backend.
  const HandleFeedbackSubmit = () => {
    if (score === null) {
      console.log("Please provide feedback before submitting.");
      return;
    }

    setLoading(true);

    axios
      .post("http://localhost:5000/chatbot/feedback", {
        response: assistantResponse,
        score: score,
        feedback: feedbackSubmitted //sends a detailed feedback back too the model. 
      })
      .then(response => {
        console.log("Feedback submitted successfully:", response.data);
        setFeedbackSubmitted(true); // Marks the feedback as submitted
        setScore(null); //Resets the score
        setFeedbackText("") //clears the feedback text.
      })
      .catch(error => {
        console.error("Error submitting feedback:", error);
      })
      .finally(() => {
        setLoading(false); //Hide, resets the loading state
      });
  };



  
  return (
    <div className="PageContainer">
      <div className="GPTBackground">
        <h1>LR-improvement Assistant</h1>
        <div className="userinputcontainer">
          <div>
          { conversationHistory.length > 1 && ( 
            <>
            {conversationHistory.map((msg, index) => (
              <p key={index} className={msg.role === "user" ? "User-P" : "Assistant-P"}>
                {msg.role === "user" ? `User: ${msg.content}` : `LR-ChatAssistant: ${msg.content}`} 
              </p>
            ))}
            </>
          )}
          </div>
   
          <input 
           placeholder="Ask LR-ChatAssistant" 
           className="ChatBox" 
           type="text"
           value={Usermessage}
            onChange={e => setUsermessage(e.target.value)}  // sets the input as user types.
            />
        </div>
        <button className="sendmessage" onClick={RequestAssistantResponse} disabled={loading}>
          {loading ? "sending..." : "Send Message"}
        </button>


        <div className="Feedback-Container">
          <button
            onClick={() => handleFeedback(1)}
            disabled={feedbackSubmitted || loading}
          >
            👍 Positive
          </button>
          <button
            onClick={() => handleFeedback(-1)}
            disabled={feedbackSubmitted || loading}
          >
            👎 Negative
          </button>
          <textarea
          placeholder="Provide additional feedback (optional)"
          value={feedbackText}
          onChange={handleFeedbackTextChange}
          disabled={feedbackSubmitted || loading}
          />
          <button onClick={HandleFeedbackSubmit} disabled={loading}>
           {loading ? "Submitting feedback..." : "submit feedback"}
          </button>
        </div>
        <div />
      </div>
    </div>
  );
}

export default Chatbot;
