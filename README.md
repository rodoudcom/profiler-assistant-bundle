# Profiler Assistant Bundle


![Symfony](https://img.shields.io/badge/Symfony-7.0+-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)
![Packagist Version](https://img.shields.io/packagist/v/rodoud/profiler-assistant-bundle)
![Packagist Downloads](https://img.shields.io/packagist/dt/rodoud/profiler-assistant-bundle)

**An AI-powered profiler assistant for Symfony applications that helps developers understand and fix errors through intelligent analysis and interactive chat support.**

*Developed by [Rodoud.com](https://rodoud.com) 🚀*


## ✨ Features

### 🤖 **Intelligent Error Analysis**
- **AI-Powered Diagnostics**: Automatically analyzes exceptions with context-aware explanations
- **Smart Categorization**: Identifies error types (Configuration, Database, Routing, etc.)
- **Solution Suggestions**: Provides actionable steps to resolve issues

### 💬 **Interactive AI Chat**
- **Real-time Assistance**: Chat with AI about your specific Symfony issues
- **Context Awareness**: AI has full access to error details and environment info
- **Conversational Help**: Ask follow-up questions and get detailed explanations

### 🔍 **Universal Error Handling**
- **Profiler Integration**: Enhanced Symfony profiler with AI insights
- **500 Error Coverage**: Handles critical errors when profiler isn't available
- **YAML Syntax Errors**: Catches configuration mistakes before they break your app
- **Missing Bundle Detection**: Identifies and helps resolve dependency issues

### 🎯 **Developer-Friendly**
- **Beginner Focused**: Perfect for developers learning Symfony
- **Stack Trace Analysis**: Clean, readable stack traces focused on your code
- **Environment Context**: Shows PHP version, Symfony version, and environment details

## 📦 Installation

### Via Composer

```bash
composer require rodoud/profiler-assistant-bundle --dev
```

### Bundle Registration

Add the bundle to your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Rodoud\ProfilerAssistantBundle\RodoudProfilerAssistantBundle::class => ['dev' => true, 'test' => true],
];
```

### Routing Configuration

Add routing to your `config/routes.yaml`:

```yaml
rodoud_profiler_assistant:
    resource: '@RodoudProfilerAssistantBundle/src/Controller/'
    type: attribute
    prefix: /rodoud
```


## 🚀 Usage

### Basic Usage

Once installed, the Profiler Assistant automatically:

1. **Enhances your Symfony profiler** with AI-powered error analysis
~~2. **Intercepts 500 errors** when profiler isn't available~~
3. **Provides intelligent suggestions** for common issues
4. **Enables AI chat** for personalized help


### AI Chat Features

The AI assistant can help with:

- **Error Explanation**: "What does this error mean?"
- **Step-by-step Solutions**: "How do I fix this configuration issue?"
- **Best Practices**: "What's the recommended way to handle this?"
- **Code Examples**: "Show me the correct syntax for this YAML config"
- **References and links**: "Shows helpful links or docs related to the detected error"

## 🎨 Screenshots

### Enhanced Error Page
![Error Page](https://raw.githubusercontent.com/rodoudcom/profiler-assistant-bundle/refs/heads/main/docs/rodoud-ai-assistant-1.png)

### AI Chat Interface
![Chat Interface](https://raw.githubusercontent.com/rodoudcom/profiler-assistant-bundle/refs/heads/main/docs/rodoud-ai-assistant-2.png)


## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 About the Author

**Adnen Chouibi**  
📧 [adnen.chouibi@gmail.com](mailto:adnen.chouibi@gmail.com)  
🌐 [Linkedin](https://www.linkedin.com/in/adnen-chouibi-b933a84a/)  


### About Rodoud.com

[Rodoud.com](https://rodoud.com) is a startup focused on building smart automation tools for customer service, e-commerce, and AI.
We use technologies like Symfony and modern AI to create real-world solutions for businesses.
We love sharing our knowledge and supporting the Symfony community with useful tools and ideas.


<div align="center">

**Made with ❤️ by [Rodoud.com](https://rodoud.com)**

*If this bundle helped you, consider giving it a ⭐ on GitHub!*

[![GitHub stars](https://img.shields.io/github/stars/rodoudcom/profiler-assistant-bundle?style=social)](https://github.com/rodoudcom/profiler-assistant-bundle)

</div>