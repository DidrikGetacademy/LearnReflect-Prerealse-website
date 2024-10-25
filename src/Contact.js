import React from 'react';
import CheckDatabaseConnection from './checkconnection';
function ContactNavBar(){
    return(
    <div className='ContactButton-Navbar'>
      <CheckDatabaseConnection/>
    </div>
    );
}
export default ContactNavBar;