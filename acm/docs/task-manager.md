
## Task Manager

L2jMobius ACM comes with a task manager that lets you execute in-game tasks.

### Requirements
In order for the task manager to work, you must include it to your gameserver's code. You can find a working example at https://gitlab.com/-/snippets/3644316.

The above example is based in CT_0 Interlude project. Place it under `gameserver/taskmanager/AcmTaskManager.java` and then include it at `GameServer.java`

```java
diff --git a/java/org/l2jmobius/gameserver/GameServer.java b/java/org/l2jmobius/gameserver/GameServer.java
index 1d8eba0..eb09cd0 100644
--- a/java/org/l2jmobius/gameserver/GameServer.java
+++ b/java/org/l2jmobius/gameserver/GameServer.java
@@ -143,6 +143,7 @@
 import org.l2jmobius.gameserver.network.NpcStringId;
 import org.l2jmobius.gameserver.network.SystemMessageId;
 import org.l2jmobius.gameserver.scripting.ScriptEngineManager;
+import org.l2jmobius.gameserver.taskmanager.AcmTaskManager;
 import org.l2jmobius.gameserver.taskmanager.GameTimeTaskManager;
 import org.l2jmobius.gameserver.taskmanager.ItemsAutoDestroyTaskManager;
 import org.l2jmobius.gameserver.taskmanager.TaskManager;
@@ -371,6 +372,7 @@
 			FishingChampionshipManager.getInstance();
 		}
 		TaskManager.getInstance();
+		AcmTaskManager.getInstance();
 		
 		AntiFeedManager.getInstance().registerEvent(AntiFeedManager.GAME_ID);
 		if (Config.CUSTOM_MAIL_MANAGER_ENABLED)
```

### How to enable
In order to enable the task manager, visit your ACM settings page. 

### How to use
The task manager consists of two main actions. Global tasks and character tasks. 

You can add a global task using the option "Task Manager" from your main menu.
You can view the character tasks by opening a character in the ACM.
