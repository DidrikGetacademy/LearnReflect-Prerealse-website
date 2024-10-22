import React from 'react'
import Checkout from './Checkout'
import { PayPalScriptProvider, } from "@paypal/react-paypal-js";
function Payment() {
    const initialoptions = {
        "client-id": "AQTrkWTmd3pfVlhl_bNhKbCyjeVh0Yf-O-fsWLPw0sRgrzWwdPOCx0o_YFKSmoFfxkdecE0ySjfxoWPt", 
        currency: "USD", 
        intent: "capture", 
      }
    return (
        
        <PayPalScriptProvider options={initialoptions}>
            <Checkout />
        </PayPalScriptProvider>
    )
}
export default Payment;