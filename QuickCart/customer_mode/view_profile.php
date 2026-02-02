<div class="profile-header">
    <h2>My Profile</h2>
</div>

<div class="profile-card">
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-user"></i> Name</span>
        <span class="profile-value"><?php echo "$cust_name"; ?></span>
    </div>
    
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-map-marker-alt"></i> Address</span>
        <span class="profile-value"><?php echo "$cust_address"; ?></span>
    </div>
    
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-phone"></i> Phone</span>
        <span class="profile-value"><?php echo "$phone"; ?></span>
    </div>
    
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-envelope"></i> Email</span>
        <span class="profile-value"><?php echo "$email"; ?></span>
    </div>
    
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-venus-mars"></i> Gender</span>
        <span class="profile-value"><?php echo "$gender"; ?></span>
    </div>
    
    <form action='profile_page.php?customer_id=<?php echo "$cust_id"; ?>&edit_profile' method="post">
        <button type="submit" name="edit_profile" class="edit-btn">
            <i class="fas fa-edit"></i> Edit Profile
        </button>
    </form>
</div>
