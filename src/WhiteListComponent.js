<<<<<<< HEAD
import React, { useState } from "react";
import axios from "axios";
import "./Css/Whitelist.css";
function Whitelist() {
  const [EmailInput, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [EmailConfirmation, setEmailConfirmation] = useState(false);
  const [Errormsg, setErrormsg] = useState("");
  const Handlesubmit = e => {
    e.preventDefault();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (EmailInput === "") {
      alert("Email is required");
      return;
    }

    if (!emailRegex.test(EmailInput)) {
      alert("Please enter a valid email address");
      return;
    }

    setLoading(true);

    axios
      .post("https://learnreflects.com/Server/save_email_with_spamblock.php", {
        email: EmailInput
      })
      .then(response => {
        console.log(response.data);
        setEmailConfirmation(true);
        setEmail("");
        setLoading(false);
      })
      .catch(err => {
        console.log(err.response ? err.response.data : err.message);
        setErrormsg(err.response ? err.response.data.error : "An unexpected error occurred") 
        setLoading(false);
      });
  };

  const handleinput = event => {
    setEmail(event.target.value);
  };

  const closeconfirmation = () => {
    setEmailConfirmation(false);
  };
  return (
    <div className="whitelist-container">
      <form className="EmailForm" onSubmit={Handlesubmit}>
        <input
          onChange={handleinput}
          type="email"
          placeholder="Email Address"
          value={EmailInput}
        />
        <button className="submitbutton" type="submit" disabled={loading}>
          {loading ? "Sending..." : "Get Early Access"}
        </button>
        {Errormsg && <p  className="error-message">{Errormsg}  <button>Close</button></p>}
      </form>

      {EmailConfirmation &&
        <div className="modal">
          <div className="modal-content">
            <p className="Thankyou">Congratulations!</p>
            <p className="P-Recieved"> we will keep you updated!</p>
            <button onClick={closeconfirmation} className="close-button">
              Close
            </button>
          </div>
        </div>}
      <p className="P-EarlyAccess">

      </p>
    </div>
  );
}

export default Whitelist;
=======
import React, { useEffect, useState } from "react";
import axios from "axios";
import "./Css/Whitelist.css";
function Whitelist() {
  const [EmailInput, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [EmailConfirmation, setEmailConfirmation] = useState(false);
  const [Errormsg, setErrormsg] = useState("");
  const Handlesubmit = e => {
    e.preventDefault();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    useEffect( () => {
      console.log("Emailconfirmation value:", EmailConfirmation)
    },[EmailConfirmation]);


    if (EmailInput === "") {
      alert("Email is required");
      return;
    }

    if (!emailRegex.test(EmailInput)) {
      alert("Please enter a valid email address");
      return;
    }

    setLoading(true);

    axios.post("https://learnreflects.com/Server/save_email_with_spamblock.php", {
        email: EmailInput
      })
      .then(response => {
        console.log(response.data);
        setEmailConfirmation(true);
        setEmail("");
        setLoading(false);
      })
      .catch(err => {
        console.log(err.response ? err.response.data : err.message);
        setErrormsg(err.response ? err.response.data.error : "An unexpected error occurred") 
        setLoading(false);
      })
  };

  const handleinput = event => {
    setEmail(event.target.value);
  };

  const closeconfirmation = () => {
    setEmailConfirmation(false);
  };

  
  return (
    <div className="whitelist-container">
      <form className="EmailForm" onSubmit={Handlesubmit}>
        <input
          onChange={handleinput}
          type="email"
          placeholder="Email Address"
          value={EmailInput}
        />
        <button className="submitbutton" type="submit" disabled={loading}>
          {loading ? "Sending..." : "Get Early Access"}
        </button>
        {Errormsg && <p  className="error-message">{Errormsg}  <button>Close</button></p>}
      </form>

      {EmailConfirmation &&
        <div className="modal">
          <div className="modal-content">
            <p className="Thankyou">Congratulations!</p>
            <p className="P-Recieved"> we will keep you updated!</p>
            <button onClick={closeconfirmation} className="close-button">
              Close
            </button>
          </div>
        </div>}
      <p className="P-EarlyAccess">

      </p>
    </div>
  );
}

export default Whitelist;
>>>>>>> e92031d0 (updated github)
