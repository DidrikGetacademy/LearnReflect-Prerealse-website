import React, { useState } from "react";
import axios from "axios";
import './Css/Whitelist.css'

function Whitelist() {
  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false); 
 const [EmailConfirmation,setEmailConfirmation] = useState(false);
  const Handlesubmit = e => {
    e.preventDefault();

   
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
      alert("Email is required");
      return;
    }

    if (!emailRegex.test(email)) {
      alert("Please enter a valid email address");
      return;
    }

    setLoading(true);

    axios
      .post("https://learnreflects.com/Server/save_email.php", {
        email: email
      })
      .then(response => {
        console.log(response.data);
        <emailConfirmation />;
        setEmailConfirmation(true);
        setEmail("");
        setLoading(false);
      })
      .catch(err => {
        console.log(err.response ? err.response.data : err.message);
        alert(
          err.response
            ? err.response.data.error
            : "An unexpected error occurred"
        );
        setLoading(false);
      });
  };

  const handleinput = event => {
    setEmail(event.target.value);
  };


    const closeconfirmation = () => {
      setEmailConfirmation(false);
    }
  return (
    <div>

    <form className="EmailForm" onSubmit={Handlesubmit}>
      <input
        onChange={handleinput}
        type="email"
        placeholder="Email Address"
        value={email}
      />
      <button className="submitbutton" type="submit" disabled={loading}>
        {loading ? "Sending..." : "Send"}
      </button>
    </form>

    {EmailConfirmation && (
      <div className="modal">
        <div className="modal-content">
        <p>Thank you</p>
            <p className="P-Recieved"> we have recieved your email!</p>
             <button onClick={closeconfirmation} className="close-button">Close</button>
        </div>
      </div>
    )}
    </div>
  );
}

export default Whitelist;
