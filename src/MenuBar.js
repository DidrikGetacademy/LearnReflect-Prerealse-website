import React, { useState } from 'react'
import './Css/Menubar.css'
import { useNavigate, Link } from 'react-router-dom'
import contactimg from './Images/contact.png'
import whitelistimg from './Images/whitelist.png'
export default function MenuBar() {
    const navigate = useNavigate();
    const [activesection, setActiveSection] = useState(false);
    const [imagesize,setimagesize] = useState({

        height:  {
            whitelist: 5,
            contact:75,
        },

        width: {
            whitelist: 10,
            contact:75,
        }
      
    });

    const handletoggle = (section) => {
        setActiveSection(activesection === section ? '' : section)
    }

    

    function DescriptionModal({ title, description, onClose }) {
        return (
            <div className='Description-overlay'>
                <div className='Description'>
                    <h2>{title}</h2>
                    <p>{description}</p>
                </div>
            </div>
        );
    }
    

return (
    <div className='menubar'>
        <div className='Menubar-list'>
            <ul style={{backgroundImage: `url(${whitelistimg})`,  backgroundSize: 'cover',
  backgroundPosition: 'center', backgroundRepeat: 'no-repeat',  Height:`${imagesize.height.whitelist}`, width: `${imagesize.width.whitelist}`}} className='Whitelist' onClick={() => navigate('/PageComponent')}></ul>


            
            <ul onClick={() => handletoggle('VideoUpscaler')}>LearnReflect's  AI Videoupscaler
            {activesection === 'VideoUpscaler' && (
                    <DescriptionModal title='Video Upscaler' description='
                    
                    
                    ' />
                )}
            </ul>



            <ul onClick={() => handletoggle('AudioUpscaler')}> LearnReflect's  AI AudioUpscaler
                {activesection === 'AudioUpscaler' && (
                    <DescriptionModal title='Audio Upscaler' description='   Audio Upscaling Software
                    Our upscaling program, Audio Enc, is designed to elevate your audio experience to new heights.
                    With advanced sound enhancement algorithms, Audio Enc enables you to convert and improve the quality of music, podcasts, and other audio content.
                    The software supports multiple file formats and delivers crystal-clear sound, providing listeners with a richer and more immersive experience.
                    Whether you are a professional audio engineer or a music enthusiast, Audio Enc will help you achieve optimal sound quality with ease and efficiency.
                    modell, Audio Denoise modell, Vocal remover/isolator modell.' />
                )}

            </ul>
            <ul onClick={() => handletoggle('description')}>LearnReflect's  AI Chatbot
                {activesection === 'description' && (
                    <DescriptionModal title='' description='    LR-Chatbot: Your Personalized Self-Improvement Assistant
                    Introducing the LR-Chatbot, an integral component of the LearnReflect self-improvement platform, designed to empower your personal growth journey.
                    This AI-driven chatbot leverages advanced machine learning techniques to provide tailored guidance and support as you work towards achieving your goals.
                    Pre-trained specifically for self-improvement, the LR-Chatbot engages in meaningful conversations, continuously learning from your interactions to adapt to your unique aspirations.
                    Whether youre striving to build discipline, enhance motivation, or develop effective daily routines, this chatbot becomes a personal companion dedicated to your success.
                    The LR-Chatbot offers personalized discipline-building strategies and motivation techniques, ensuring that you remain focused and accountable on your journey.
                    With its ability to understand your evolving needs, the chatbot provides insights and encouragement that resonate with you, making self-improvement an achievable goal.
                    In conjunction with our advanced AI models for enhancing video and audio quality, LearnReflect serves as an all-in-one solution for anyone committed to self-improvement and productivity.
                    Whether youre aiming to boost daily habits or pursue long-term achievements, the LR-Chatbot is here to guide you every step of the way, helping you become the best version of yourself.' onClose={() => setActiveSection('')}/>
                )  }


            </ul>
            <ul onClick={() => {navigate('./ContactNavBar')}} style={{backgroundImage: `url(${contactimg})`, height: `${imagesize.height.contact}px`, width: `${imagesize.width.contact}`, backgroundSize: 'cover'}}>
                {activesection === 'contact' && <li>


                </li>}
            </ul>
        </div>
    </div>
)

}