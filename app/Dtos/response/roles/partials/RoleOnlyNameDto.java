package com.melardev.spring.twitterapi.dtos.response.roles.partials;

import com.melardev.spring.twitterapi.models.Role;
import org.springframework.security.core.GrantedAuthority;

public class RoleOnlyNameDto {
    private final String name;

    public RoleOnlyNameDto(String name) {
        this.name = name;
    }

    public static RoleOnlyNameDto build(Role role) {
        return new RoleOnlyNameDto(role.getName());
    }

    public static RoleOnlyNameDto build(GrantedAuthority role) {
        return new RoleOnlyNameDto(role.getAuthority());
    }

    public static String buildAsString(GrantedAuthority role) {
        return role.getAuthority();
    }

    public String getName() {
        return name;
    }
}
