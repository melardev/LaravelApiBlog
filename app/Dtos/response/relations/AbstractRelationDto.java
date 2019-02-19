package com.melardev.spring.twitterapi.dtos.response.relations;

public abstract class AbstractRelationDto {
    private String username;
    private String imageUrl;
    private String profileDescription;

    public AbstractRelationDto(String username, String imageUrl, String profileDescription) {
        this.username = username;
        this.imageUrl = imageUrl;
        this.profileDescription = profileDescription;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getImageUrl() {
        return imageUrl;
    }

    public void setImageUrl(String imageUrl) {
        this.imageUrl = imageUrl;
    }

    public String getProfileDescription() {
        return profileDescription;
    }

    public void setProfileDescription(String profileDescription) {
        this.profileDescription = profileDescription;
    }
}
