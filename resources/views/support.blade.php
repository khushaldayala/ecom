<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Your Website</title>
    <style>
        /* Reset some default styles */
        body,
        h1,
        h2,
        p {
            margin: 0;
            padding: 0;
        }

        /* Apply basic styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f9fd;
            color: #333;
        }

        header {
            background-color: #0076FF;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #0076FF;
        }

        h2 {
            font-size: 20px;
            margin-top: 20px;
            color: #0076FF;
            cursor: pointer;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #0076FF;
            color: #fff;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 20px;
            }

            h2 {
                font-size: 18px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>FAQ</h1>
    </header>
    <main class="container">
        <section class="faq-section">
            <h2 class="faq-question">Question 1: What is Lorem Ipsum?</h2>
            <div class="faq-answer">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam gravida odio nec ante varius.</p>
            </div>
        </section>
        <section class="faq-section">
            <h2 class="faq-question">Question 2: How can I contact support?</h2>
            <div class="faq-answer">
                <p>You can contact our support team via email at <a
                        href="mailto:support@example.com">support@example.com</a>.</p>
            </div>
        </section>
        <!-- Add more FAQ sections as needed -->
    </main>
    <footer>
        <p>&copy; 2023 Your Website</p>
        
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const faqQuestions = document.querySelectorAll(".faq-question");

            faqQuestions.forEach(function (question) {
                question.addEventListener("click", function () {
                    const answer = this.nextElementSibling;
                    answer.classList.toggle("show-answer");
                });
            });
        });

    </script>
</body>

</html>