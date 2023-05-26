Current Feature Architecture Notice : 

1. Auto inactive account functionality is triggered on the following :
    - when the user logs into their account
    - when the bonus is destributed
        - this is on registration
        - on Level Commision trigger


Current then Future Suggestions : 

1. Registration codes (first layer security implemented - front end validation).
    - This can be improved in the future when we implmeent 2nd layer security on the backend site upon registering with registration code.
    - Tech notes : can be injected in wpmlm_registration_page.
    
2. Suggestion for above #1, is to trigger a CRON job - a robot that will check and update each users daily.