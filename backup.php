<style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to bottom, #004c91, #87CEEB);
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            width: 100%;
            height: 80px;
            background: #004c91;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .header nav a {
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            background: white;
            color: #004c91;
            border: 2px solid #004c91;
            transition: all 0.3s ease;
        }
        .header nav a:hover {
            background: #f0f0f0;
        }
        .container {
            max-width: 1200px;
            width: 90%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #004c91;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }
        .filter-group {
            display: none; /* Initially hidden */
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        select, button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        select:hover {
            border-color: #004c91;
        }
        button {
            background-color: #004c91;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #003366;
        }
        .scrollable-table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 1rem;
            word-wrap: break-word;
        }
        table th {
            background: #004c91;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: auto;
            background: #004c91;
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
        }
    </style>