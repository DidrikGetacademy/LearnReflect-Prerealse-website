import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Css/Menubar.css';
import { useNavigate } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
export default function MenuBar() {
    const navigate = useNavigate();
    // State for modal
    const [show, setShow] = useState(false);
    const [modalContent, setModalContent] = useState({ title: '', action: '',Requirements: '',Features: '' });
    const [IsInStock, Setisinstock] = useState(true);
    const handleClose = () => {
        setShow(false);
        Setisinstock(false);
    }

    const handleShow = (title, action, Requirements, Features) => {
        setModalContent({ title, action, Requirements, Features });
        setShow(true);
    };

    const handlepayment = () => {
        navigate('./Payment')
    }



    return (
        <div className='MenuBar-Container'>
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <div className="container">
                    <div className="row">
                        {[
                            { title: 'Buy VideoUpscaler AI', action: '',Features: '',Requirements: '' },
                            { title: 'Whitelist' },
                            { title: 'LearnReflect AI - GPT Assistant', action: 'LR-Chatbot: Your Personalized Self-Improvement Assistant. Introducing the LR-Chatbot, an integral component of the LearnReflect self-improvement platform, designed to empower your personal growth journey. This AI-driven chatbot leverages advanced machine learning techniques to provide tailored guidance and support as you work towards achieving your goals. Pre-trained specifically for self-improvement, the LR-Chatbot engages in meaningful conversations, continuously learning from your interactions to adapt to your unique aspirations. Whether you’re striving to build discipline, enhance motivation, or develop effective daily routines, this chatbot becomes a personal companion dedicated to your success. The LR-Chatbot offers personalized discipline-building strategies and motivation techniques, ensuring that you remain focused and accountable on your journey. With its ability to understand your evolving needs, the chatbot provides insights and encouragement that resonate with you, making self-improvement an achievable goal. In conjunction with our advanced AI models for enhancing video and audio quality, LearnReflect serves as an all-in-one solution for anyone committed to self-improvement and productivity.' },
                            { title: 'Contact', },
                      
                        ].map((item, index) => (
                            <div className="col-12 mb-3" key={index}>
                                <span
                                    onClick={() => {
                                        if (item.title === ('Whitelist')) {
                                            navigate('./PageComponent')
                                        } else if (item.title === 'Contact') {
                                            navigate('./Contact');
                                        } else {
                                            handleShow(item.title, item.action);
                                        }
                                        if (item.title === 'AI Video Enhancer') {
                                            Setisinstock(true);
                                        }
                                    }}
                                    className="title-span">
                                    {item.title}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </nav>


            <Modal show={show} className='Modal fade' size="m" onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>{modalContent.title}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {modalContent.action}
                </Modal.Body>
                <Modal.Footer>
                    {IsInStock ?
                        <div>
                            <Button variant="secondary" onClick={handleClose}>Close</Button>
                            <Button variant="secondary" onClick={handlepayment}>Buy</Button>
                        </div>
                        :
                        <Button variant="secondary" onClick={handleClose}>Close</Button>
                    }

                </Modal.Footer>
            </Modal>

        </div>
    );
}
