const fs = require('fs');

const footerCss = `
    <style>
        .site-footer {
            background: #000000;
            color: #ffffff;
            padding: 60px 0 30px;
            font-family: 'Inter', sans-serif;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 60px;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 80px;
        }

        .footer-logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 24px;
        }

        .social-icons {
            display: flex;
            gap: 12px;
        }

        .social-icon {
            width: 44px;
            height: 44px;
            border: 1px solid #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            background: #5CC4E5;
            border-color: #5CC4E5;
            color: #000000;
        }

        .footer-newsletter {
            display: flex;
            flex-direction: column;
        }

        .footer-heading {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .newsletter-desc {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #5CC4E5;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .newsletter-form {
            display: flex;
            align-items: center;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .newsletter-input {
            flex: 1;
            padding: 16px 18px;
            border: none;
            background: transparent;
            color: #000000;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
        }

        .newsletter-input::placeholder {
            color: #000000;
        }

        .newsletter-btn {
            width: 50px;
            height: 50px;
            background: transparent;
            border: none;
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .newsletter-btn:hover {
            color: #5CC4E5;
        }

        .footer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-list li {
            margin-bottom: 12px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-list a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-list a:hover {
            color: #5CC4E5;
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 50px auto 0;
            padding: 25px 60px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 50px;
                padding: 0 30px;
                text-align: center;
            }

            .footer-logo {
                margin: 0 auto 24px;
            }

            .social-icons {
                justify-content: center;
            }

            .site-footer {
                padding: 40px 0 20px;
            }
        }
    </style>
`;

const files = ['produit-aisar.html', 'produit-aihrus.html', 'produit-aivish.html'];

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    if (!content.includes('.footer-container {')) {
        content = content.replace('</head>', footerCss + '\n</head>');
        fs.writeFileSync(file, content);
        console.log('Added footer CSS to ' + file);
    } else {
        console.log('Footer CSS already exists in ' + file);
    }
});
