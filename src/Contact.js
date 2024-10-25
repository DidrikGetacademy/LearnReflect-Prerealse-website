<<<<<<< HEAD
import React from 'react';
function ContactNavBar(){
    return(
    <div className='ContactButton-Navbar'>

    </div>
    );
}
=======
import React from 'react';
import CheckDatabaseConnection from './checkconnection';
function ContactNavBar(){
    return(
    <div className='ContactButton-Navbar'>
      <CheckDatabaseConnection/>
    </div>
    );
}
>>>>>>> e92031d0 (updated github)
export default ContactNavBar;