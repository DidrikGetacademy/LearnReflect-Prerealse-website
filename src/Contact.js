import React, { useState } from 'react';
import axios from 'axios';
import './Css/Contact.css';
import { useNavigate } from 'react-router-dom';
function ContactNavBar() {
  const [email, setEmail] = useState("");
  const [category, setcategory] = useState("");
  const [loading, setLoading] = useState(false);
  const [description, setDescription] = useState("");
  const Navigate = useNavigate();

  
  const HandleChange = async (event) => {
    event.preventDefault();
    setLoading(true);
    try {
      const response = await axios.post('https://learnreflects.com/Server/contact_request.php', {
        EmailAddress: email,
        Category: category,
        Description: description,
      })
      console.log('Response',response.data)
    } catch(error){
      console.error('error.',error);

    } finally {
      setLoading(false);
      setEmail("");
      return( alert("success"));
    }
  }

  return (
    <div className='ContactDiv'>
      <form onSubmit={HandleChange}>
        <label>Support Request</label>
        <input  onChange={(e) => setEmail(e.target.value)} placeholder='Email Address' type='Email' required />
        <select value={category} onChange={(e) =>  setcategory(e.target.value)} required>
          <option value="" disabled>select an option</option>
          <option value="Business">Business</option>
          <option value="Account">Account & Security</option>
          <option value="Payment">Payment</option>
        </select>
        <textarea onChange={(e) => setDescription(e.target.value)} className='Description' placeholder='Skriv din beskrivelse her...' required>

        </textarea>
        <button  disabled={loading}  type='submit'>{loading ? 'Loading...' : 'Submit'}</button>
        <button onClick={() => Navigate("/") }>Cancel</button>
      </form>
    </div>
  );
}
export default ContactNavBar; 