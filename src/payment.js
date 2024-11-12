import React from 'react'
import Checkout from './Checkout'
import { PayPalScriptProvider, } from "@paypal/react-paypal-js";
function Payment() {
    const initialoptions = {
        "client-id": "AcnQT0n7Xq_qaWASBn0R25WCqE-zqOtpXdZwah04i4WYY5dcuz8hf3gDZTJ7lK7wLew-13VZ4DBoY18n", 
        currency: "USD", 
        intent: "capture", 
      }
    return (
        
        <PayPalScriptProvider options={initialoptions}>
            <Checkout/>
        </PayPalScriptProvider>
    )
}
export default Payment;