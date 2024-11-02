import React, { useState } from "react";
import { PayPalButtons, usePayPalScriptReducer } from "@paypal/react-paypal-js"; //PayPal script is loading and can be used to change the values of the options of the PayPal script and at the same time reload the script with the updated parameters.
import upscaled from './Images/bedre.jpg'
import './Css/checkout.css';
import ImageCarousel from "./ImageCarousel";
function Checkout() {
    const [{ options, isPending }, dispatch] = usePayPalScriptReducer();// paypal script reducer manage a way to manage the state of the paypalscript, sutchs as client side with buttons, if a customer choose another currency, reducer will update this.
    const [currency, setCurrency] = useState(options.currency);

    const onCurrencyChange = ({ target: { value } }) => {
        setCurrency(value);
        dispatch({ type: 'resetOptions', value: { ...options, currency: value, }, });
    }


    const onCreateOrder = (data, actions) => {
        return actions.order.create({
            purchase_units: [{ amount: { value: "20.00", }, },],
        });
    }


    const onApprove = (data, actions) => {
        return actions.order.capture().then((details) => {
            const name = details.payer.name.given_name; alert(`Transaction completed by ${name}`);
        });
    };



    return (
        <div style={{ display: "flex", alignItems: "center", height: "100vh", width: "100vw", justifyContent: "center" }}>
            <img className="stuker" alt="background" src={upscaled} />
            <div className="checkout">
                <ImageCarousel/>
                <div className="payment-section">
                    <label>Please choose a payment method below</label>
                    {isPending ? (
                        <p>Loading...</p>
                    ) : (
                        <>
                            <select className="currency-select" value={currency} onChange={onCurrencyChange}>
                                <option className="option" value="USD">💵 USD</option>
                                <option className="option" value="EUR">💶 Euro</option>
                            </select>
                        </>
                    )}
                    <PayPalButtons
                        style={{ layout: "vertical" }}
                        createOrder={(data, actions) => onCreateOrder(data, actions)}
                        onApprove={(data, actions) => onApprove(data, actions)}
                        onError={(err) => console.error("Paypal error: ", err)}
                    />
                </div>
            </div>
        </div>
    );


}

export default Checkout;

