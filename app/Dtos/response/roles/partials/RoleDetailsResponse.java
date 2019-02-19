package com.melardev.spring.twitterapi.dtos.response.roles.partials;

import com.melardev.spring.twitterapi.models.Role;

public class RoleDetailsResponse {
    private String name;
    private String description;
    private int userCount;

    public RoleDetailsResponse(String name, String description, int userCount) {
        this.name = name;
        this.description = description;
        this.userCount = userCount;
    }

    public static RoleDetailsResponse build(Role role) {
        return new RoleDetailsResponse(role.getName(), role.getDescription(), role.getUsers().size());
    }
}
