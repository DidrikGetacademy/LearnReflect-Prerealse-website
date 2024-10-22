import React, { useState } from "react";
import { PayPalButtons, usePayPalScriptReducer } from "@paypal/react-paypal-js"; //PayPal script is loading and can be used to change the values of the options of the PayPal script and at the same time reload the script with the updated parameters.
import './Css/checkout.css';
function Checkout() {
    const [{ options, isPending }, dispatch] = usePayPalScriptReducer();// paypal script reducer manage a way to manage the state of the paypalscript, sutchs as client side with buttons, if a customer choose another currency, reducer will update this.
    const [currency, setCurrency] = useState(options.currency);
    const onCurrencyChange = ({ target: { value } }) => {
        setCurrency(value);
        dispatch({ type: 'resetOptions', value: { ...options, currency: value, }, });
    }


    const onCreateOrder = (data, actions) => { return actions.order.create({ purchase_units: [{ amount: { value: "20.00", }, },], }); }
    const onApprove = (data, actions) => { return actions.order.capture().then((details) => { const name = details.payer.name.given_name; alert(`Transaction completed by ${name}`); }); }



    return (
        <div className="checkout">
            {isPending ? <p>Loading...</p> : (<> <select value={currency} onChange={onCurrencyChange}>
                <option className="option" value="USD">💵 USD</option>
                <option className="option" value="EUR">💶 Euro</option>
            </select></>)}
            <PayPalButtons style={{ layout: "vertical" }} createOrder={(data, actions) => onCreateOrder(data, actions)} onApprove={(data, actions) => onApprove(data, actions)} />
        </div>
    )
}

export default Checkout;

