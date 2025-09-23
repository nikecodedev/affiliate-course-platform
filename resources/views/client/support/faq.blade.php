@extends('layouts.client')

@section('title', 'FAQ')
@section('page-title', 'Frequently Asked Questions')

@section('content')
<div class="row">
    <!-- FAQ Search -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="text-center mb-4">
                            <h4>How can we help you?</h4>
                            <p class="text-muted">Search our frequently asked questions or browse by category.</p>
                        </div>
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" id="faqSearch" placeholder="Search FAQ...">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- FAQ Categories -->
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Categories
                </h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action active" data-category="all">
                    <i class="bi bi-grid me-2"></i>
                    All Questions
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="account">
                    <i class="bi bi-person-circle me-2"></i>
                    Account & Profile
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="financial">
                    <i class="bi bi-currency-dollar me-2"></i>
                    Financial & Payments
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="leads">
                    <i class="bi bi-people me-2"></i>
                    Lead Management
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="training">
                    <i class="bi bi-book me-2"></i>
                    Training & Courses
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="technical">
                    <i class="bi bi-gear me-2"></i>
                    Technical Support
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-category="billing">
                    <i class="bi bi-receipt me-2"></i>
                    Billing & Invoices
                </a>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-headset me-2"></i>
                    Still Need Help?
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Can't find what you're looking for?</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('client.support.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create Support Ticket
                    </a>
                    <a href="mailto:support@example.com" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-envelope me-2"></i>
                        Email Support
                    </a>
                    <a href="tel:+1234567890" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-telephone me-2"></i>
                        Call Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-question-circle me-2"></i>
                    Frequently Asked Questions
                </h5>
            </div>
            <div class="card-body">
                <!-- Account & Profile -->
                <div class="faq-category" data-category="account">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-person-circle me-2"></i>
                        Account & Profile
                    </h6>
                    
                    <div class="accordion mb-4" id="accountAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#account1">
                                    How do I update my profile information?
                                </button>
                            </h2>
                            <div id="account1" class="accordion-collapse collapse show" data-bs-parent="#accountAccordion">
                                <div class="accordion-body">
                                    You can update your profile information by going to <strong>My Profile</strong> in the sidebar. 
                                    Note that your email and CPF cannot be changed for security reasons. If you need to update these, 
                                    please contact our support team.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#account2">
                                    How do I change my password?
                                </button>
                            </h2>
                            <div id="account2" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                                <div class="accordion-body">
                                    To change your password, go to <strong>My Profile > Security</strong> and click on "Change Password". 
                                    You'll need to enter your current password and then your new password twice for confirmation.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#account3">
                                    What are tracking tags and how do I use them?
                                </button>
                            </h2>
                            <div id="account3" class="accordion-collapse collapse" data-bs-parent="#accountAccordion">
                                <div class="accordion-body">
                                    Tracking tags allow you to integrate analytics and marketing tools like Facebook Pixel, 
                                    Google Analytics, and Google Tag Manager into your capture sites. You can configure these 
                                    in <strong>My Profile > Tracking Tags</strong>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial & Payments -->
                <div class="faq-category" data-category="financial">
                    <h6 class="text-success mb-3">
                        <i class="bi bi-currency-dollar me-2"></i>
                        Financial & Payments
                    </h6>
                    
                    <div class="accordion mb-4" id="financialAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#financial1">
                                    How do I request a withdrawal?
                                </button>
                            </h2>
                            <div id="financial1" class="accordion-collapse collapse" data-bs-parent="#financialAccordion">
                                <div class="accordion-body">
                                    To request a withdrawal, go to <strong>Financial > Withdrawals</strong> and click "Request Withdrawal". 
                                    You'll need to enter the amount and confirm with your CPF password (last 4 digits). 
                                    Withdrawals are processed within 1-3 business days.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#financial2">
                                    What are the withdrawal fees?
                                </button>
                            </h2>
                            <div id="financial2" class="accordion-collapse collapse" data-bs-parent="#financialAccordion">
                                <div class="accordion-body">
                                    Withdrawal fees vary by amount:
                                    <ul>
                                        <li>Under R$ 100: R$ 5.00 fee</li>
                                        <li>R$ 100 - R$ 500: R$ 10.00 fee</li>
                                        <li>Above R$ 500: R$ 15.00 fee</li>
                                    </ul>
                                    These fees help cover processing costs and are deducted from your withdrawal amount.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#financial3">
                                    How do I view my transaction history?
                                </button>
                            </h2>
                            <div id="financial3" class="accordion-collapse collapse" data-bs-parent="#financialAccordion">
                                <div class="accordion-body">
                                    You can view your complete transaction history in <strong>Financial > Transactions</strong>. 
                                    You can filter by type (credit/debit), status, and date range. You can also export your 
                                    transaction data for record keeping.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead Management -->
                <div class="faq-category" data-category="leads">
                    <h6 class="text-info mb-3">
                        <i class="bi bi-people me-2"></i>
                        Lead Management
                    </h6>
                    
                    <div class="accordion mb-4" id="leadsAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#leads1">
                                    How do I add new leads?
                                </button>
                            </h2>
                            <div id="leads1" class="accordion-collapse collapse" data-bs-parent="#leadsAccordion">
                                <div class="accordion-body">
                                    You can add leads manually by going to <strong>Leads > New Lead</strong> or import them in bulk 
                                    using the import feature. The system supports CSV files with columns for name, email, 
                                    phone, source, status, and notes.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#leads2">
                                    What lead statuses are available?
                                </button>
                            </h2>
                            <div id="leads2" class="accordion-collapse collapse" data-bs-parent="#leadsAccordion">
                                <div class="accordion-body">
                                    The available lead statuses are:
                                    <ul>
                                        <li><strong>Active</strong> - Lead is engaged and responsive</li>
                                        <li><strong>Pending</strong> - Waiting for response or follow-up</li>
                                        <li><strong>Inactive</strong> - Lead is not responding</li>
                                        <li><strong>Converted</strong> - Lead has made a purchase</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Training & Courses -->
                <div class="faq-category" data-category="training">
                    <h6 class="text-warning mb-3">
                        <i class="bi bi-book me-2"></i>
                        Training & Courses
                    </h6>
                    
                    <div class="accordion mb-4" id="trainingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#training1">
                                    How do I access the training courses?
                                </button>
                            </h2>
                            <div id="training1" class="accordion-collapse collapse" data-bs-parent="#trainingAccordion">
                                <div class="accordion-body">
                                    Training courses are available to clients with active subscriptions that include course access. 
                                    Go to <strong>Training</strong> in the sidebar to view available courses. If you don't see the 
                                    training section, you may need to upgrade your plan.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#training2">
                                    How do I track my course progress?
                                </button>
                            </h2>
                            <div id="training2" class="accordion-collapse collapse" data-bs-parent="#trainingAccordion">
                                <div class="accordion-body">
                                    Your course progress is automatically tracked as you complete lessons. You can view your 
                                    progress in the <strong>Training > Progress</strong> section, which shows completion 
                                    percentages for each course and module.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Support -->
                <div class="faq-category" data-category="technical">
                    <h6 class="text-danger mb-3">
                        <i class="bi bi-gear me-2"></i>
                        Technical Support
                    </h6>
                    
                    <div class="accordion mb-4" id="technicalAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#technical1">
                                    How do I get my tracking code?
                                </button>
                            </h2>
                            <div id="technical1" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body">
                                    You can download your tracking code from <strong>My Profile > Tracking Code</strong>. 
                                    This code should be added to your website to track visitors and conversions from your 
                                    capture sites.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#technical2">
                                    What browsers are supported?
                                </button>
                            </h2>
                            <div id="technical2" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body">
                                    Our platform supports all modern browsers including Chrome, Firefox, Safari, and Edge. 
                                    For the best experience, we recommend using the latest version of your preferred browser.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing & Invoices -->
                <div class="faq-category" data-category="billing">
                    <h6 class="text-secondary mb-3">
                        <i class="bi bi-receipt me-2"></i>
                        Billing & Invoices
                    </h6>
                    
                    <div class="accordion mb-4" id="billingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing1">
                                    How do I download my invoices?
                                </button>
                            </h2>
                            <div id="billing1" class="accordion-collapse collapse" data-bs-parent="#billingAccordion">
                                <div class="accordion-body">
                                    You can download your invoices from <strong>Financial > Invoices</strong>. Click on any 
                                    invoice to view details and download the PDF. You can also upload payment receipts 
                                    if required.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing2">
                                    What payment methods are accepted?
                                </button>
                            </h2>
                            <div id="billing2" class="accordion-collapse collapse" data-bs-parent="#billingAccordion">
                                <div class="accordion-body">
                                    We accept various payment methods including:
                                    <ul>
                                        <li>Credit/Debit Cards (Visa, Mastercard)</li>
                                        <li>Bank Transfer (PIX, Boleto)</li>
                                        <li>PayPal</li>
                                        <li>Cryptocurrency (Bitcoin, Ethereum)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // FAQ Search functionality
    document.getElementById('faqSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        accordionButtons.forEach(button => {
            const text = button.textContent.toLowerCase();
            const accordionItem = button.closest('.accordion-item');
            
            if (text.includes(searchTerm)) {
                accordionItem.style.display = '';
            } else {
                accordionItem.style.display = 'none';
            }
        });
    });
    
    // Category filtering
    document.querySelectorAll('[data-category]').forEach(category => {
        category.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active category
            document.querySelectorAll('[data-category]').forEach(cat => {
                cat.classList.remove('active');
            });
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            const faqCategories = document.querySelectorAll('.faq-category');
            
            faqCategories.forEach(category => {
                if (selectedCategory === 'all' || category.dataset.category === selectedCategory) {
                    category.style.display = '';
                } else {
                    category.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
