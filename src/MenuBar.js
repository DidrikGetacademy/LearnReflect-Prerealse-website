import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Css/Menubar.css';
import { useNavigate } from 'react-router-dom';
import { Modal, Button } from 'react-bootstrap';
export default function MenuBar() {
    const navigate = useNavigate();
    // State for modal
    const [show, setShow] = useState(false);
    const [modalContent, setModalContent] = useState({ title: '', action: '' });
    const [IsInStock, Setisinstock] = useState(false);
    const handleClose = () => {
        setShow(false);
        Setisinstock(false);
    }

    const handleShow = (title, action) => {
        setModalContent({ title, action });
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
                            { title: 'Whitelist' },
                            { title: 'LearnReflect AI - GPT Assistant', action: 'LR-Chatbot: Your Personalized Self-Improvement Assistant. Introducing the LR-Chatbot, an integral component of the LearnReflect self-improvement platform, designed to empower your personal growth journey. This AI-driven chatbot leverages advanced machine learning techniques to provide tailored guidance and support as you work towards achieving your goals. Pre-trained specifically for self-improvement, the LR-Chatbot engages in meaningful conversations, continuously learning from your interactions to adapt to your unique aspirations. Whether you’re striving to build discipline, enhance motivation, or develop effective daily routines, this chatbot becomes a personal companion dedicated to your success. The LR-Chatbot offers personalized discipline-building strategies and motivation techniques, ensuring that you remain focused and accountable on your journey. With its ability to understand your evolving needs, the chatbot provides insights and encouragement that resonate with you, making self-improvement an achievable goal. In conjunction with our advanced AI models for enhancing video and audio quality, LearnReflect serves as an all-in-one solution for anyone committed to self-improvement and productivity.' },
                            { title: 'AI Audio Enhancer', action: 'Audio Upscaling Software. Our upscaling program, Audio Enc, is designed to elevate your audio experience to new heights. With advanced sound enhancement algorithms, Audio Enc enables you to convert and improve the quality of music, podcasts, and other audio content. The software supports multiple file formats and delivers crystal-clear sound, providing listeners with a richer and more immersive experience. Whether you are a professional audio engineer or a music enthusiast, Audio Enc will help you achieve optimal sound quality with ease and efficiency.' },
                            { title: 'AI Video Enhancer', action: 'LearnReflects AI Video Upscaler is a powerful tool designed to elevate your video content to new levels of clarity and detail. Using advanced AI algorithms, the Video Upscaler enhances video quality by improving resolution, reducing noise, and refining visual details in your footage. Whether you’re working with low-resolution videos or simply want to enhance the visual quality of your content, the Video Upscaler transforms your videos into crisp high-definition formats. This technology is perfect for content creators, video professionals, or anyone who wants their visual content to stand out. The Video Upscaler works seamlessly alongside LearnReflects suite of AI-driven tools for personal growth, productivity, and self-improvement, ensuring that you not only improve yourself but also the quality of your work.' },
                            { title: 'Contact', },
                            { title: 'Donate/Support Project', action: 'Support the Development of LearnReflects: Contribute to the Future of Personal Growth through AI Techniques' }
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
