import React from "react";
import './Css/modal.css'

const Modal = ({ message, details, onClose }) => {
    return(
        <div className="modal-overlay">
            <div className="modal-content">
                <h2>{message}</h2>
                <h3>{details}</h3>
                <button className="ModalButton" onClick={onClose}>Close</button>
            </div>
        </div>
    );
};

export default Modal;