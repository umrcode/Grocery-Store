

<div class="profile-header">
    <h2>My Profile</h2>
</div>

<div class="profile-card">
    <div class="profile-field">
        <span class="profile-label"><i class="fas fa-user"></i> Name</span>
        <span class="profile-value"><?php echo "$agent_name"; ?></span>
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
        <span class="profile-label"><i class="fas fa-wallet"></i> Total Earnings</span>
        <span class="profile-value">Rs.<?php echo "$earning_total"; ?></span>
    </div>
    
    <form action='profile_page.php?agent_id=<?php echo "$agent_id"; ?>&edit_profile' method="post">
        <button type="submit" name="edit_profile" class="edit-btn">
            <i class="fas fa-edit"></i> Edit Profile
        </button>
    </form>
</div>
