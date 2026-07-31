// ==========================================
// san3a.js
// الملف المسؤول عن كل الاتصالات بالـ API بتاع صنعة
// ==========================================

const BASE_URL = '/api';

async function apiCall(endpoint, method = 'GET', body = null, requiresAuth = true) {
  const token = localStorage.getItem('token');
  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };
  if (requiresAuth && token) {
    headers['Authorization'] = `Bearer ${token}`;
  }
  const options = { method, headers };
  if (body) options.body = JSON.stringify(body);

  try {
    const response = await fetch(`${BASE_URL}${endpoint}`, options);
    const data = await response.json();
    if (!response.ok) {
      const errorMessage = data.message || 'حصل خطأ غير متوقع';
      const validationErrors = data.errors ? JSON.stringify(data.errors) : '';
      throw new Error(`${errorMessage} ${validationErrors}`);
    }
    return data;
  } catch (error) {
    console.error(`API Error [${endpoint}]:`, error.message);
    throw error;
  }
}

// ==========================================
// Auth & Identity (إسراء)
// ==========================================

async function requestOtp(phone, purpose = 'login') {
  return apiCall('/auth/request-otp', 'POST', { phone, purpose }, false);
}

async function verifyOtp(phone, code, purpose = 'login', role = null) {
  const body = { phone, code, purpose };
  if (purpose === 'register' && role) body.role = role;
  const data = await apiCall('/auth/verify-otp', 'POST', body, false);
  if (data.token) localStorage.setItem('token', data.token);
  return data;
}

async function logout() {
  const data = await apiCall('/auth/logout', 'POST', null, true);
  localStorage.removeItem('token');
  return data;
}

// ==========================================
// Craftsman Profile & Verification (إسراء)
// ==========================================

async function createCraftsmanProfile(professionId, zoneIds, yearsExperience, bio) {
  return apiCall('/craftsman/profile', 'POST', {
    profession_id: professionId,
    zone_ids: Array.isArray(zoneIds) ? zoneIds : [zoneIds],
    years_experience: yearsExperience,
    bio: bio,
  }, true);
}

async function getCraftsmanProfile() {
  return apiCall('/craftsman/profile', 'GET', null, true);
}

async function uploadVerificationDoc(docType, file, nationalIdNumber = null, cardNumber = null) {
  const token = localStorage.getItem('token');
  const formData = new FormData();
  formData.append('doc_type', docType);
  formData.append('file', file);
  if (nationalIdNumber) formData.append('national_id_number', nationalIdNumber);
  if (cardNumber) formData.append('card_number', cardNumber);

  const response = await fetch(`${BASE_URL}/verification/upload`, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` }),
    },
    body: formData,
  });

  const data = await response.json();
  if (!response.ok) throw new Error(data.message || 'حصل خطأ في رفع الملف');
  return data;
}

// ==========================================
// Admin - Verification (إسراء)
// ==========================================

async function getVerificationRequests() {
  return apiCall('/admin/verification-requests', 'GET', null, true);
}

async function approveVerificationRequest(documentId) {
  return apiCall(`/admin/verification-requests/${documentId}/approve`, 'POST', null, true);
}

async function rejectVerificationRequest(documentId, rejectionReason = '') {
  return apiCall(`/admin/verification-requests/${documentId}/reject`, 'POST', { rejection_reason: rejectionReason }, true);
}

// ==========================================
// Wallet, Payments & Growth Levers (بلال)
// ==========================================

async function getWallet() {
  return apiCall('/wallet', 'GET', null, true);
}

async function walletHold(amount, description = null, referenceId = null, jobId = null) {
  return apiCall('/wallet/hold', 'POST', { amount, description, reference_id: referenceId, job_id: jobId }, true);
}

async function walletConfirm(amount, description = null, referenceId = null, jobId = null) {
  return apiCall('/wallet/confirm', 'POST', { amount, description, reference_id: referenceId, job_id: jobId }, true);
}

async function walletRelease(amount, description = null, referenceId = null, jobId = null) {
  return apiCall('/wallet/release', 'POST', { amount, description, reference_id: referenceId, job_id: jobId }, true);
}

async function getWalletTransactions(page = 1) {
  return apiCall(`/wallet/transactions?page=${page}`, 'GET', null, true);
}

async function getSubscriptionPlans() {
  return apiCall('/subscriptions/plans', 'GET', null, false);
}

async function getCurrentSubscription() {
  return apiCall('/subscriptions/current', 'GET', null, true);
}

async function subscribeToPlan(planId) {
  return apiCall('/subscriptions/subscribe', 'POST', { plan_id: planId }, true);
}

async function cancelSubscription() {
  return apiCall('/subscriptions/cancel', 'POST', null, true);
}

async function getPayments(page = 1) {
  return apiCall(`/payments?page=${page}`, 'GET', null, true);
}

async function createPayment(amount, method, jobId = null) {
  return apiCall('/payments', 'POST', { job_id: jobId, amount, method }, true);
}

async function confirmCashPayment(paymentId, craftsmanId, professionId = null) {
  return apiCall('/payments/confirm-cash', 'POST', {
    payment_id: paymentId,
    craftsman_id: craftsmanId,
    profession_id: professionId,
  }, true);
}

async function getPayouts() {
  return apiCall('/payouts', 'GET', null, true);
}

async function requestPayout(amount, method) {
  return apiCall('/payouts', 'POST', { amount, method }, true);
}

async function getAdminPayouts() {
  return apiCall('/admin/payouts', 'GET', null, true);
}

async function approvePayout(payoutId) {
  return apiCall(`/admin/payouts/${payoutId}/approve`, 'POST', null, true);
}

async function rejectPayout(payoutId) {
  return apiCall(`/admin/payouts/${payoutId}/reject`, 'POST', null, true);
}

// ==========================================
// Matching, Requests & Contracts (الاء)
// ==========================================

async function getJobRequests(page = 1) {
  return apiCall(`/job-requests?page=${page}`, 'GET', null, true);
}

async function createJobRequest(professionId, description, zoneId, address, preferredTime = null) {
  return apiCall('/job-requests', 'POST', {
    profession_id: professionId,
    description,
    zone_id: zoneId,
    address,
    preferred_time: preferredTime,
  }, true);
}

async function getAvailableJobRequests() {
  return apiCall('/job-requests/available', 'GET', null, true);
}

async function createProposal(jobRequestId, priceQuote, message = null) {
  return apiCall(`/job-requests/${jobRequestId}/proposals`, 'POST', {
    price_quote: priceQuote,
    message,
  }, true);
}

async function acceptProposal(proposalId) {
  return apiCall(`/proposals/${proposalId}/accept`, 'POST', null, true);
}

async function startOnTheWay(jobId) {
  return apiCall(`/jobs/${jobId}/start-on-the-way`, 'POST', null, true);
}

async function pingLocation(jobId, lat, lng) {
  return apiCall(`/jobs/${jobId}/ping-location`, 'POST', { lat, lng }, true);
}

async function requestCloseOtp(jobId) {
  return apiCall(`/jobs/${jobId}/request-close-otp`, 'POST', null, true);
}

async function confirmCloseOtp(jobId, otpCode) {
  return apiCall(`/jobs/${jobId}/confirm-close-otp`, 'POST', { code: otpCode }, true);
}

async function cancelJob(jobId, reason) {
  return apiCall(`/jobs/${jobId}/cancel`, 'POST', { reason }, true);
}

async function confirmCancellationTrap(jobId, confirmed) {
  return apiCall(`/jobs/${jobId}/confirm-cancellation-trap`, 'POST', { craftsman_attempted_work: confirmed }, true);
}

async function reportJob(jobId, defectDescription, workDoneDescription, costBreakdown, beforePhotos = null, afterPhotos = null) {
  return apiCall(`/jobs/${jobId}/report`, 'POST', {
    defect_description: defectDescription,
    work_done_description: workDoneDescription,
    cost_breakdown: costBreakdown,
    before_photos: beforePhotos,
    after_photos: afterPhotos,
  }, true);
}

async function acknowledgeJobReport(jobId) {
  return apiCall(`/jobs/${jobId}/report/client-ack`, 'POST', null, true);
}

// ==========================================
// Ratings, Pricing, Warranty (زياد)
// ==========================================

async function createRating(jobId, ratedUserId, direction, score, behaviorScore = null, comment = null) {
  return apiCall('/ratings', 'POST', {
    job_id: jobId,
    rated_user_id: ratedUserId,
    direction,
    score,
    behavior_score: behaviorScore,
    comment,
  }, true);
}

async function getUserRatings(userId) {
  return apiCall(`/ratings/user/${userId}`, 'GET', null, true);
}

async function getPricingRules(professionId = null) {
  const query = professionId ? `?profession_id=${professionId}` : '';
  return apiCall(`/pricing/rules${query}`, 'GET', null, false);
}

async function getStandardizedServices(professionId = null) {
  const query = professionId ? `?profession_id=${professionId}` : '';
  return apiCall(`/pricing/standardized-services${query}`, 'GET', null, false);
}

async function getWarrantyClaims(page = 1) {
  return apiCall(`/warranty-claims?page=${page}`, 'GET', null, true);
}

async function createWarrantyClaim(jobId, issueDescription, claimType) {
  return apiCall('/warranty-claims', 'POST', {
    job_id: jobId,
    issue_description: issueDescription,
    claim_type: claimType,
  }, true);
}

async function getWarrantyClaim(claimId) {
  return apiCall(`/warranty-claims/${claimId}`, 'GET', null, true);
}
